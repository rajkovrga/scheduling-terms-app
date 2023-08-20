<?php

namespace SchedulingTerms\App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SchedulingTerms\App\Contracts\Repositories\JobRepositoryContract;
use SchedulingTerms\App\Core\Routing\Attributes\DeleteRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GroupRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;

#[GroupRoute('jobs')]
class JobController
{
    public function __construct(
        private JobRepositoryContract $jobRepository
    )
    {
    }

    #[GetRoute('')]
    public function getJobs(ServerRequestInterface $request,ResponseInterface $response) {

    }

    #[GetRoute('/{id}')]
    public function getJob(ServerRequestInterface $request,ResponseInterface $response, int $id) {
        return $response->withJson($this->jobRepository->get(2), 201);
    }

    #[PostRoute('')]
    public function createJob(ServerRequestInterface $request,ResponseInterface $response) {

    }

    #[DeleteRoute('/{id}')]
    public function deleteJob(ServerRequestInterface $request,ResponseInterface $response, int $id) {

    }

    #[PutRoute('/{id}')]
    public function editJob(ServerRequestInterface $request,ResponseInterface $response, int $id) {

    }
}