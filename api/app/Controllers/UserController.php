<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Controllers;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rakit\Validation\RuleQuashException;
use SchedulingTerms\App\Contracts\Repositories\UserRepositoryContract;
use SchedulingTerms\App\Core\Routing\Attributes\DeleteRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GroupRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;
use SchedulingTerms\App\Dto\Users\CreateUpdateUserDto;
use SchedulingTerms\App\Helpers\Hasher;
use SchedulingTerms\App\Http\Resources\Users\UserResource;
use SchedulingTerms\App\Http\Validators\Users\UserRequestValidator;
use SchedulingTerms\App\Models\User;

#[GroupRoute('/users')]
readonly class UserController
{
    public function __construct(
        private UserRepositoryContract $userRepository,
        private Hasher $hasher
    )
    {
    }
    #[GetRoute('/paginate/{cursor}')]
    public function getUsers(ServerRequestInterface $request,ResponseInterface $response, ?string $cursor = null) {
        $data = $this->userRepository->paginate();
    
        return $response->withJson((new UserResource($data))->toCollection($data),200);
    }

    #[GetRoute('/{id}')]
    public function getUser(ServerRequestInterface $request,ResponseInterface $response, int $id) {
        // TODO: check permission
        /** @var User $user */
        $user = $this->userRepository->get($id);
    
        return $response->withJson((new UserResource($user))->toArray($request), 200);
    }

    /**
     * @throws Exception
     */
    #[PostRoute('')]
    public function createUser(ServerRequestInterface $request,ResponseInterface $response) {
        // TODO: check permission
        $data = $request->getParsedBody();
    
        $validator = new UserRequestValidator($request);
        $result = $validator->validated($data);
    
        if($result->fails()) {
            return $response->withJson($result->errors()->toArray(), 409);
        }
    
        $term = $this->userRepository->create(new CreateUpdateUserDto(
            $data['email'],
            $data['company_id'],
            $this->hasher->randomPassword(),
            $data['role_id'],
        ));
    
        return $response->withJson((new UserResource($term))->toArray($request), 201);
    }

    #[DeleteRoute('/{id}')]
    public function deleteUser(ServerRequestInterface $request ,ResponseInterface $response, int $id): ResponseInterface
    {
        $this->userRepository->delete($id);
    
        return $response->withStatus(204);
    }

    #[PutRoute('/{id}')]
    public function editUser(ServerRequestInterface $request,ResponseInterface $response, int $id) {
        $data = $request->getParsedBody();
    
        $validator = new UserRequestValidator($request);
        $result = $validator->validated($data);
    
        if($result->fails()) {
            return $response->withJson($result->errors()->toArray(), 409);
        }
    
        $user = $this->userRepository->update($id, new CreateUpdateUserDto(
            $data['email'],
            $data['company_id'],
            null,
            $data['role_id'],
        ));
    
        return $response->withJson((new UserResource($user))->toArray($request), 200);
    }
}