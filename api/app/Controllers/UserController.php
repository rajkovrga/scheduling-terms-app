<?php

namespace SchedulingTerms\App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SchedulingTerms\App\Core\Routing\Attributes\DeleteRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GroupRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;

#[GroupRoute('users')]
class UserController
{
    #[GetRoute('/')]
    public function getUsers(ServerRequestInterface $request,ResponseInterface $response) {
        return $response->withJson([], 200);
    }

    #[GetRoute('/{id}')]
    public function getUser(ServerRequestInterface $request,ResponseInterface $response, int $id) {
        return $response->withJson([$id], 200);
    }

    #[PostRoute('/')]
    public function createUser(ServerRequestInterface $request,ResponseInterface $response) {
        return $response->withJson([], 200);
    }

    #[DeleteRoute('/{id}')]
    public function deleteUser(ServerRequestInterface $request,ResponseInterface $response, int $id) {

    }

    #[PutRoute('/{id}')]
    public function editUser(ServerRequestInterface $request,ResponseInterface $response, int $id) {

    }
}