<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Controllers;

use Cake\Database\Type\JsonType;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SchedulingTerms\App\Contracts\Repositories\CompanyRepositoryContract;
use SchedulingTerms\App\Core\Routing\Attributes\DeleteRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GroupRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;
use SchedulingTerms\App\Dto\Companies\CompanyDto;
use SchedulingTerms\App\Dto\Companies\CreateUpdateCompanyDto;
use SchedulingTerms\App\Dto\Companies\UpdateCompanyDto;
use SchedulingTerms\App\Http\Resources\Companies\CompanyResource;
use SchedulingTerms\App\Http\Validators\Companies\CompanyRequestValidator;

#[GroupRoute('/companies')]
class CompanyController
{
    public function __construct(
        public CompanyRepositoryContract $companyRepository
    )
    {
    }
    
    #[GetRoute('/{cursor}')]
    public function getCompanies(ServerRequestInterface $request,ResponseInterface $response): ResponseInterface {

        return $response;
    }

    #[GetRoute('/{id}')]
    public function getCompany(ServerRequestInterface $request,ResponseInterface $response, int $id): ResponseInterface {
        
        // TODO: check permission
        /** @var CompanyDto $company */
        $company = $this->companyRepository->get($id);
    
        return $response->withJson(CompanyResource::toArray($company), 200);
    }

    #[PostRoute('/')]
    public function createCompany(CompanyRequestValidator $request, ResponseInterface $response): ResponseInterface {
        // TODO: check permission
        
        $validator = $request->validated($request['data']);
        
        if($validator->fails()) {
            return $response->withJson($validator->errors(), 409);
        }
        
        $company = $this->companyRepository->create(CreateUpdateCompanyDto::from($request['data']));
        
        return $response->withJson($company, 201);
    }

    #[DeleteRoute('{id}')]
    public function deleteCompany(ServerRequestInterface $request,ResponseInterface $response, int $id): ResponseInterface {
    
        return $response;
    }

    #[PutRoute('{id}')]
    public function editCompany(CompanyRequestValidator $request,ResponseInterface $response, int $id): ResponseInterface {
        $validator = $request->validated($request['data']);
    
        if($validator->fails()) {
            return $response->withJson($validator->errors(), 409);
        }
    
        $company = $this->companyRepository->update($id, UpdateCompanyDto::from($request['data']));
    
        return $response->withJson($company, 204);
    }
}