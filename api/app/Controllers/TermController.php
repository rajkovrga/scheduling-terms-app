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
use SchedulingTerms\App\Http\Validators\Terms\TermRequestValidator;

#[GroupRoute('terms')]
class TermController
{
    #[GetRoute('/{cursor}')]
    public function getTerms(ServerRequestInterface $request, ResponseInterface $response)
    {
    
    }
    
    #[GetRoute('/{id}')]
    public function getTerm(ServerRequestInterface $request, ResponseInterface $response, int $id)
    {
    
    }
    
    #[PostRoute('/')]
    public function createTerm(TermRequestValidator $request, ResponseInterface $response)
    {
        $validator = $request->validated($request['data']);
        
        if ($validator->fails()) {
            return $response->withJson($validator->errors(), 409);
        }
        
        return $response->withJson([], 204);
    }
    
    #[DeleteRoute('/{id}')]
    public function deleteTerm(ServerRequestInterface $request, ResponseInterface $response, int $id)
    {
    
    }
    
    #[PutRoute('/{id}')]
    public function editTerm(ServerRequestInterface $request, ResponseInterface $response, int $id)
    {
    
    }
}