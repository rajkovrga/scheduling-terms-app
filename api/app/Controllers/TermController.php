<?php

namespace SchedulingTerms\App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SchedulingTerms\App\Core\Routing\Attributes\DeleteRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GroupRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;

#[GroupRoute('terms')]
class TermController
{
    #[GetRoute('')]
    public function getTerms(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }

    #[GetRoute('/{id}')]
    public function getTerm(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }

    #[PostRoute('')]
    public function createTerm(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }

    #[DeleteRoute('/{id}')]
    public function deleteTerm(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }

    #[PutRoute('/{id}')]
    public function editTerm(ServerRequestInterface $request,ResponseInterface $response, array $args) {

    }
}