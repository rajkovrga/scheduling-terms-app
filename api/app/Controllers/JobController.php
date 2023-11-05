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
use SchedulingTerms\App\Http\Validators\Jobs\JobRequestValidator;

#[GroupRoute('jobs', ['auth'])]
class JobController
{
    public function __construct(
        private JobRepositoryContract $jobRepository
    )
    {
    }

    #[GetRoute('/{cursor}',  ['auth'])]
    public function getJobs(ServerRequestInterface $request,ResponseInterface $response) {

    }

    #[GetRoute('/{id}')]
    public function getJob(ServerRequestInterface $request,ResponseInterface $response, int $id) {
        return $response->withJson($this->jobRepository->get(2), 201);
    }

    #[PostRoute('/')]
    public function createJob(JobRequestValidator $request, ResponseInterface $response) {
        $validator = $request->validated($request['data']);
        
        if($validator->fails()) {
            return $response->withJson($validator->errors(), 409);
        }
    
        return $response->withJson([], 204);
    }

    #[DeleteRoute('/{id}')]
    public function deleteJob(ServerRequestInterface $request,ResponseInterface $response, int $id) {

    }

    #[PutRoute('/{id}')]
    public function editJob(JobRequestValidator $request,ResponseInterface $response, int $id) {

    }
}