<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rakit\Validation\RuleQuashException;
use SchedulingTerms\App\Contracts\Repositories\AuthRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\TokenRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\UserRepositoryContract;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;
use SchedulingTerms\App\Dto\Tokens\CreateTokenDto;
use SchedulingTerms\App\Helpers\Hasher;
use SchedulingTerms\App\Http\AppRequest;
use SchedulingTerms\App\Http\Resources\Users\UserResource;
use SchedulingTerms\App\Http\Validators\Auth\ChangePasswordValidator;
use SchedulingTerms\App\Http\Validators\Auth\ForgotPasswordValidator;
use SchedulingTerms\App\Http\Validators\Auth\LoginValidator;
use Slim\Psr7\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

readonly class AuthController
{
    public function __construct(
//        private IEmailService           $emailService,
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

        $user = $this->userRepository->findByEmail($data['email']);

        if (!password_verify($data['password'], $user->password)) {
            return $response->withStatus(403);
        }

        if($this->tokenRepository->getByUserId($user->id)) {
            return $response->withJson('User just logged in', 401);
        }

        $token = $this->hasher->hashToken(str_shuffle($user->email));

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
        return $response->withJson((new UserResource($request->user()))->toArray($request), 201);
    }
    
    #[GetRoute('/auth/permissions', ['auth'])]
    public function permissions(AppRequest $request, ResponseInterface $response): ResponseInterface
    {
        return $response->withJson($this->authRepositoryContract->getPermissions($request->user()->roleId), 201);
    }

    #[PostRoute('/forget-password')]
    public function forgetPassword(ServerRequestInterface $request, ResponseInterface $response, string $token): ResponseInterface
    {
        $data = $request->getParsedBody();
    
        $validator = new ForgotPasswordValidator($request);
        $result = $validator->validated($data);
    
        if ($result->fails()) {
            return $response->withJson($result->errors()->toArray(), 409);
        }
        
        $user = $this->userRepository->getByEmail($data['email']);
        
        $this->emailService->send($user->email, 'Recovery Password', '<h1>test</h1>');
        
        return $response->withStatus(201);
    }

    #[PutRoute('/change-password/{token}')]
    public function changePassword(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();
    
        $validator = new ChangePasswordValidator($request);
        $result = $validator->validated($data);
    
        if ($result->fails()) {
            return $response->withJson($result->errors()->toArray(), 409);
        }
        
        

        return $response->withStatus(204);
    }

}