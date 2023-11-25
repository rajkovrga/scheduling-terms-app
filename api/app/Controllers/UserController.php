<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Controllers;

use Exception;
use Psr\Http\Message\ResponseInterface;
use SchedulingTerms\App\Contracts\Repositories\UserRepositoryContract;
use SchedulingTerms\App\Contracts\Services\IEmailService;
use SchedulingTerms\App\Core\Routing\Attributes\DeleteRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GroupRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;
use SchedulingTerms\App\Dto\Users\CreateUpdateUserDto;
use SchedulingTerms\App\Exceptions\PermissionDeniedException;
use SchedulingTerms\App\Helpers\Hasher;
use SchedulingTerms\App\Http\AppRequest;
use SchedulingTerms\App\Http\Resources\Pagination\PaginationResource;
use SchedulingTerms\App\Http\Resources\Users\UserResource;
use SchedulingTerms\App\Http\Validators\Users\UserRequestValidator;
use SchedulingTerms\App\Utils\Permissions;

#[GroupRoute('/users', ['auth'])]
readonly class UserController
{
    public function __construct(
        private UserRepositoryContract $userRepository,
        private Hasher $hasher,
        public IEmailService $emailService
    )
    {
    }
    #[GetRoute('/paginate/{cursor:0}')]
    public function getUsers(AppRequest $request,ResponseInterface $response, int $cursor = 0) {
        if($request->can(Permissions::ViewAllUsers)) {
            $data = $this->userRepository->paginate($cursor);
        }
    
        if($request->can(Permissions::ViewUsers)) {
            $data = $this->userRepository->paginateByCompanyId($cursor);
        }
        
        return $response->withJson((new PaginationResource($request))->toArray((new UserResource($request))->toCollection($data)),200);
    }
    
    /**
     * @throws PermissionDeniedException
     */
    #[GetRoute('/{id}')]
    public function getUser(AppRequest $request,ResponseInterface $response, int $id) {
        if(!$request->can(Permissions::ViewAllUsers) || $id !== $request->user()->company->id) {
            throw new PermissionDeniedException();
        }
        
        $user = $this->userRepository->get($id);
    
        return $response->withJson((new UserResource($request))->toArray($user), 200);
    }

    /**
     * @throws Exception
     */
    #[PostRoute('')]
    public function createUser(AppRequest $request,ResponseInterface $response) {
        $request->checkPermission(Permissions::CreateUser, $response);
    
        $data = $request->getParsedBody();
    
        $validator = new UserRequestValidator($request);
        $result = $validator->validated($data);
    
        if($result->fails()) {
            return $response->withJson($result->errors()->toArray(), 409);
        }
    
        $password = $this->hasher->randomPassword();
        
        $user = $this->userRepository->create(new CreateUpdateUserDto(
            $data['email'],
            $data['company_id'],
            $this->hasher->hashPassword($password),
            $data['role_id'],
        ));
        
        $this->emailService->sendNewCreatedUser($user->email, $password, $request);
    
        return $response->withJson((new UserResource($request))->toArray($user), 201);
    }

    #[DeleteRoute('/{id}')]
    public function deleteUser(AppRequest $request ,ResponseInterface $response, int $id): ResponseInterface
    {
        $request->checkPermission(Permissions::DeleteUser, $response);
    
        $this->userRepository->delete($id);
    
        return $response->withStatus(204);
    }

    #[PutRoute('/{id}')]
    public function editUser(AppRequest $request,ResponseInterface $response, int $id) {
        $request->checkPermission(Permissions::EditUser, $response);
    
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
    
        return $response->withJson((new UserResource($request))->toArray($user), 200);
    }
}