<?php

namespace SchedulingTerms\App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SchedulingTerms\App\Core\Routing\Attributes\DeleteRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GroupRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;

#[GroupRoute('jobs')]
class JobController
{
    #[GetRoute('')]
    public function getJobs(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }

    #[GetRoute('/{id}')]
    public function getJob(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }

    #[PostRoute('')]
    public function createJob(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }

    #[DeleteRoute('/{id}')]
    public function deleteJob(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }

    #[PutRoute('/{id}')]
    public function editJob(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }
}