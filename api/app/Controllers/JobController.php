<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SchedulingTerms\App\Contracts\Repositories\JobRepositoryContract;
use SchedulingTerms\App\Core\Routing\Attributes\DeleteRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GroupRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;
use SchedulingTerms\App\Dto\Jobs\CreateUpdateJobDto;
use SchedulingTerms\App\Http\Resources\Jobs\JobResource;
use SchedulingTerms\App\Http\Validators\Jobs\JobRequestValidator;
use SchedulingTerms\App\Models\Job;

#[GroupRoute('/jobs', ['auth'])]
readonly class JobController
{
    public function __construct(
        private JobRepositoryContract $jobRepository
    )
    {
    }

    #[GetRoute('/paginate/{cursor}',  ['auth'])]
    public function getJobs(ServerRequestInterface $request,ResponseInterface $response) {
        $data = $this->jobRepository->paginate();
    
        return $response->withJson((new JobResource($data))->toCollection($data),200);
    }

    #[GetRoute('/{id}')]
    public function getJob(ServerRequestInterface $request,ResponseInterface $response, int $id) {
        // TODO: check permission
    
        /** @var Job $job */
        $job = $this->jobRepository->get($id);
        
        return $response->withJson((new JobResource($job))->toArray($request), 200);
    }

    #[PostRoute('')]
    public function createJob(ServerRequestInterface $request, ResponseInterface $response) {
        // TODO: check permission
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
    
        return $response->withJson((new JobResource($job))->toArray($request), 201);
    }

    #[DeleteRoute('/{id}')]
    public function deleteJob(ServerRequestInterface $request,ResponseInterface $response, int $id): ResponseInterface
    {
        $this->jobRepository->delete($id);
    
        return $response->withStatus(204);
    }

    #[PutRoute('/{id}')]
    public function editJob(ServerRequestInterface $request,ResponseInterface $response, int $id) {
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
    
        return $response->withJson((new JobResource($job))->toArray($request), 200);
    }
}