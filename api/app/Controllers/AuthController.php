<?php

namespace SchedulingTerms\App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GroupRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;

class AuthController
{

    #[PostRoute('/login')]
    public function login(ServerRequestInterface $request,ResponseInterface $response, array $args) {
    }

    #[PostRoute('/register')]
    public function register(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }

    #[GetRoute('/me')]
    public function me(ServerRequestInterface $request,ResponseInterface $response): ResponseInterface
    {
        return $response->withJson([], 201);
    }

    #[PostRoute('/forget-password/{token}')]
    public function forgetPassword(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }

    #[PutRoute('/change-password')]
    public function changePassword(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }

}