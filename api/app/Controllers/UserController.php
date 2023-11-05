<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SchedulingTerms\App\Core\Routing\Attributes\DeleteRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GroupRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;
use SchedulingTerms\App\Http\Resources\Users\UserResource;
use SchedulingTerms\App\Http\Validators\Users\UserRequestValidator;

#[GroupRoute('users')]
class UserController
{
    #[GetRoute('/{cursor}')]
    public function getUsers(ServerRequestInterface $request,ResponseInterface $response, ?string $cursor = null) {
        return $response->withJson([], 200);
    }

    #[GetRoute('/{id}')]
    public function getUser(ServerRequestInterface $request,ResponseInterface $response, int $id) {
        return $response->withJson([$id], 200);
    }

    #[PostRoute('/')]
    public function createUser(UserRequestValidator $request,ResponseInterface $response) {
        $validator = $request->validated($request['data']);
        
        if($validator->fails()) {
            return $response->withJson($validator->errors(), 409);
        }
    
        return $response->withJson([], 204);
    }

    #[DeleteRoute('/{id}')]
    public function deleteUser(ServerRequestInterface $request,ResponseInterface $response, int $id) {

    }

    #[PutRoute('/{id}')]
    public function editUser(UserRequestValidator $request,ResponseInterface $response, int $id) {

    }
}