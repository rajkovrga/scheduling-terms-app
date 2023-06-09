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
    #[GetRoute('')]
    public function getUsers(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }

    #[GetRoute('/{id}')]
    public function getUser(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }

    #[PostRoute('')]
    public function createUser(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }

    #[DeleteRoute('/{id}')]
    public function deleteUser(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }

    #[PutRoute('/{id}')]
    public function editUser(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }
}