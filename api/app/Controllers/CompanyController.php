<?php

namespace SchedulingTerms\App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SchedulingTerms\App\Core\Routing\Attributes\DeleteRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GroupRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;

#[GroupRoute('companies')]
class CompanyController
{

    #[GetRoute('')]
    public function getCompanies(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }

    #[GetRoute('/{id}')]
    public function getCompany(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }

    #[PostRoute('')]
    public function createCompany(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }

    #[DeleteRoute('/{id}')]
    public function deleteCompany(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }

    #[PutRoute('/{id}')]
    public function editCompany(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }
}