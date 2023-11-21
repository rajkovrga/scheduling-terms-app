<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Controllers;

use Cake\Database\Connection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rakit\Validation\RuleQuashException;
use Redis;
use SchedulingTerms\App\Contracts\Repositories\TokenRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\UserRepositoryContract;
use SchedulingTerms\App\Contracts\Services\IEmailService;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;
use SchedulingTerms\App\Helpers\Hasher;
use SchedulingTerms\App\Http\Validators\Auth\LoginValidator;
use SchedulingTerms\App\Http\Validators\AuthRequest;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

readonly class AuthController
{
    public function __construct(
        private IEmailService   $emailService,
        private TokenRepositoryContract $tokenRepository,
        private UserRepositoryContract $userRepository,
        private Hasher $hasher
    ) {
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

        if($result->fails()) {
            return $response->withJson($result->errors()->toArray(), 409);
        }

        $user = $this->userRepository->findByEmail($data['email']);

        if(!password_verify($data['password'], $user->password))
        {
            return $response->withStatus(403);
        }

        $token = $this->hasher->hashToken(str_shuffle($user->email));

        return $response->withStatus('')->withJson([
            'token' => $token
        ]);
    }
    
    /**
     * @throws TransportExceptionInterface
     */
    #[GetRoute('/me', ['auth'])]
    public function me(AuthRequest $request, ResponseInterface $response): ResponseInterface
    {
        return $response->withJson($request->user(), 201);
    }

    #[PostRoute('/forget-password/{token}')]
    public function forgetPassword(ServerRequestInterface $request, ResponseInterface $response, string $token): ResponseInterface
    {
    
        $this->emailService->send("rajkovrga.it@gmail.com", 'test', '<h1>test</h1>');
        return $response->withJson([], 201);
    }

    #[PutRoute('/change-password')]
    public function changePassword(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
    
        return $response->withJson([], 204);
    }

}