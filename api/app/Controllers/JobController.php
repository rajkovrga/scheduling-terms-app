<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Rakit\Validation\RuleQuashException;
use SchedulingTerms\App\Contracts\Repositories\JobRepositoryContract;
use SchedulingTerms\App\Core\Routing\Attributes\DeleteRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GroupRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;
use SchedulingTerms\App\Dto\Jobs\CreateUpdateJobDto;
use SchedulingTerms\App\Exceptions\PermissionDeniedException;
use SchedulingTerms\App\Http\AppRequest;
use SchedulingTerms\App\Http\Resources\Jobs\JobResource;
use SchedulingTerms\App\Http\Resources\Pagination\PaginationResource;
use SchedulingTerms\App\Http\Validators\Jobs\JobRequestValidator;
use SchedulingTerms\App\Utils\Permissions;

#[GroupRoute('/jobs', ['auth'])]
readonly class JobController
{
    public function __construct(
        private JobRepositoryContract $jobRepository
    )
    {
    }

    #[GetRoute('/paginate/{cursor:0}',  ['auth'])]
    public function getJobs(AppRequest $request,ResponseInterface $response, int $cursor = 0) {
        if($request->can(Permissions::ViewAllJobs)) {
            $data = $this->jobRepository->paginate($cursor);
        }
    
        if($request->can(Permissions::ViewJobs)) {
            $data = $this->jobRepository->paginateByCompanyId($cursor);
        }
        
        return $response->withJson((new PaginationResource($request))->toArray((new JobResource($request))->toCollection($data)),200);
    }
    
    /**
     * @throws PermissionDeniedException
     */
    #[GetRoute('/{id}')]
    public function getJob(AppRequest $request,ResponseInterface $response, int $id) {
        if(!$request->can(Permissions::ViewAllJobs) || $id !== $request->user()->company->id) {
            throw new PermissionDeniedException();
        }
    
        $job = $this->jobRepository->get($id);
        
        return $response->withJson((new JobResource($request))->toArray($job), 200);
    }
    
    /**
     * @throws RuleQuashException
     * @throws PermissionDeniedException
     */
    #[PostRoute('')]
    public function createJob(AppRequest $request, ResponseInterface $response) {
        $request->checkPermission(Permissions::CreateJob, $response);
        $data = $request->getParsedBody();
    
        $validator = new JobRequestValidator($request);
        $result = $validator->validated($data);
    
        if($result->fails()) {
            return $response->withJson($result->errors()->toArray(), 409);
        }
    
        $job = $this->jobRepository->create(new CreateUpdateJobDto(
            $data['name'],
            $data['during'],
            $data['company_id']
        ));
    
        return $response->withJson((new JobResource($request))->toArray($job), 201);
    }
    
    /**
     * @throws PermissionDeniedException
     */
    #[DeleteRoute('/{id}')]
    public function deleteJob(AppRequest $request,ResponseInterface $response, int $id): ResponseInterface
    {
        $request->checkPermission(Permissions::DeleteJob, $response);
    
        $this->jobRepository->delete($id);
    
        return $response->withStatus(204);
    }
    
    /**
     * @throws RuleQuashException
     * @throws PermissionDeniedException
     */
    #[PutRoute('/{id}')]
    public function editJob(AppRequest $request,ResponseInterface $response, int $id) {
        $request->checkPermission(Permissions::EditJob, $response);
    
        $data = $request->getParsedBody();
    
        $validator = new JobRequestValidator($request);
        $result = $validator->validated($data);
    
        if($result->fails()) {
            return $response->withJson($result->errors()->toArray(), 409);
        }
    
        $job = $this->jobRepository->update($id, new CreateUpdateJobDto(
            $data['name'],
            $data['during'],
            $data['company_id']
        ));
    
        return $response->withJson((new JobResource($request))->toArray($job), 200);
    }
}