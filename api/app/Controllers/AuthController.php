<?php

namespace SchedulingTerms\App\Controllers;

use Cake\Database\Connection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Redis;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;

class AuthController
{
    public function __construct(
        private readonly Connection $connection,
        private readonly Redis $redis,
    ) {
    }

    #[PostRoute('/login', ['auth'])]
    public function login(ServerRequestInterface $request, ResponseInterface $response)
    {
    }

    #[PostRoute('/register')]
    public function register(ServerRequestInterface $request, ResponseInterface $response)
    {
    }

    #[GetRoute('/me')]
    public function me(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $response->withJson([], 201);
    }

    #[PostRoute('/forget-password/{token}')]
    public function forgetPassword(ServerRequestInterface $request, ResponseInterface $response, string $token)
    {
    }

    #[PutRoute('/change-password')]
    public function changePassword(ServerRequestInterface $request, ResponseInterface $response)
    {
    }

}