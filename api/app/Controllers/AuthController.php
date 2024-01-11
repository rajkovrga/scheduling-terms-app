<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rakit\Validation\RuleQuashException;
use SchedulingTerms\App\Contracts\Repositories\AuthRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\TokenRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\UserRepositoryContract;
use SchedulingTerms\App\Contracts\Services\IEmailService;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;
use SchedulingTerms\App\Dto\Tokens\CreateTokenDto;
use SchedulingTerms\App\Exceptions\PermissionDeniedException;
use SchedulingTerms\App\Helpers\Hasher;
use SchedulingTerms\App\Http\AppRequest;
use SchedulingTerms\App\Http\Resources\Users\UserResource;
use SchedulingTerms\App\Http\Validators\Auth\ChangePasswordValidator;
use SchedulingTerms\App\Http\Validators\Auth\ForgotPasswordValidator;
use SchedulingTerms\App\Http\Validators\Auth\LoginValidator;
use SchedulingTerms\App\Utils\Permissions;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

readonly class AuthController
{
    public function __construct(
        private IEmailService           $emailService,
        private TokenRepositoryContract $tokenRepository,
        private UserRepositoryContract  $userRepository,
        private Hasher                  $hasher,
        private AuthRepositoryContract $authRepositoryContract
    )
    {
    }

    /**
     * @throws RuleQuashException
     */
    #[PostRoute('/login')]
    public function login(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();

        $validator = new LoginValidator($request);
        $result = $validator->validated($data);

        if ($result->fails()) {
            return $response->withJson($result->errors()->toArray(), 409);
        }

        $user = $this->userRepository->getByEmail($data['email']);

        if (!password_verify($data['password'], $user->password)) {
            return $response->withJson('User credentials is not correct',401);
        }
        
        $token = $this->tokenRepository->getByUserId($user->id);

        if($token) {
            return $response->withJson([
                'token' => $token->token
            ], 201);
        }

        $token = $this->hasher->hashAuthToken(str_shuffle($user->email));

        $this->tokenRepository->create(new CreateTokenDto(
            $user->id,
            $token
        ));

        return $response->withJson([
            'token' => $token
        ], 201);

    }

    /**
     * @throws TransportExceptionInterface
     */
    #[GetRoute('/me', ['auth'])]
    public function me(AppRequest $request, ResponseInterface $response): ResponseInterface
    {
        return $response->withJson((new UserResource($request))->toArray($request->user()), 201);
    }
    
    #[GetRoute('/auth/permissions', ['auth'])]
    public function permissions(AppRequest $request, ResponseInterface $response): ResponseInterface
    {
        return $response->withJson($this->authRepositoryContract->getPermissions($request->user()->roleId), 201);
    }
    
    /**
     * @throws RuleQuashException
     */
    #[PostRoute('/forget-password')]
    public function forgetPassword(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();
    
        $validator = new ForgotPasswordValidator($request);
        $result = $validator->validated($data);
    
        if ($result->fails()) {
            return $response->withJson($result->errors()->toArray(), 409);
        }
        
        $user = $this->userRepository->getByEmail($data['email']);
        
        $token = $this->hasher->hashToken();
        
        $this->authRepositoryContract->saveRecoveryToken($token,$user->id);
        $this->emailService->sendPasswordRecovery($user->email, 'Recovery Password', $request, $token);
        
        return $response->withStatus(201);
    }
    
    /**
     * @throws RuleQuashException
     */
    #[PutRoute('/change-password/{token}')]
    public function changePassword(ServerRequestInterface $request, ResponseInterface $response, string $token): ResponseInterface
    {
        $data = $request->getParsedBody();
    
        $validator = new ChangePasswordValidator($request);
        $result = $validator->validated($data);
    
        if ($result->fails()) {
            return $response->withJson($result->errors()->toArray(), 409);
        }
        
        $checkResult = $this->authRepositoryContract->checkRecoveryToken($token);
    
        if(!$checkResult) {
            return $response->withJson("Token expired", 401);
        }
        
        $user = $this->userRepository->get($checkResult);
        
        $hashPassword = $this->hasher->hashPassword($data['password']);
        
        $this->authRepositoryContract->changePassword($user->id, $hashPassword);
        
        return $response->withStatus(204);
    }
    
    #[PostRoute('/logout', ['auth'])]
    public function logout(AppRequest $request, ResponseInterface $response): ResponseInterface
    {
        $this->tokenRepository->delete($request->token);
        return $response->withStatus( 204);
    }
    
    /**
     * @throws PermissionDeniedException
     */
    public function changeProfileImage(AppRequest $request, ResponseInterface $response): ResponseInterface {
        $request->checkPermission(Permissions::EditProfile, $response);
        
        return $response->withStatus( 204);
    }
    
    #[PostRoute('/logout', ['auth'])]
    public function csrf(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        
        $token = random_bytes(rand(8,11));
        
        $return = $this->tokenRepository->createCsrf($token);
        
        return $response
            ->withJson([
                'csrf' => $token
            ])
            ->withStatus(201);
    }

}