<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SchedulingTerms\App\Contracts\Repositories\CompanyRepositoryContract;
use SchedulingTerms\App\Core\Routing\Attributes\DeleteRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GroupRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;
use SchedulingTerms\App\Dto\Companies\CreateUpdateCompanyDto;
use SchedulingTerms\App\Http\Resources\Companies\CompanyResource;
use SchedulingTerms\App\Http\Validators\Companies\CompanyRequestValidator;
use SchedulingTerms\App\Models\Company;

#[GroupRoute('/companies', ['auth'])]
readonly class CompanyController
{
    public function __construct(
        public CompanyRepositoryContract $companyRepository
    )
    {
    }
    
    #[GetRoute('/paginate/{cursor}')]
    public function getCompanies(ServerRequestInterface $request,ResponseInterface $response): ResponseInterface {
        $data = $this->companyRepository->paginate();
        
        return $response->withJson((new CompanyResource($data))->toCollection($data),200);
    }

    #[GetRoute('/{id}')]
    public function getCompany(ServerRequestInterface $request,ResponseInterface $response, int $id): ResponseInterface {
        // TODO: check permission
        /** @var Company $company */
        $company = $this->companyRepository->get($id);
    
        return $response->withJson((new CompanyResource($company))->toArray($request), 200);
    }

    #[PostRoute('')]
    public function createCompany(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        // TODO: check permission
        $data = $request->getParsedBody();
        
        $validator = new CompanyRequestValidator($request);
        $result = $validator->validated($data);
        
        if($result->fails()) {
            return $response->withJson($result->errors()->toArray(), 409);
        }
        
        $company = $this->companyRepository->create(new CreateUpdateCompanyDto($data['name']));
        
        return $response->withJson((new CompanyResource($company))->toArray($request), 201);
    }

    #[DeleteRoute('/{id}')]
    public function deleteCompany(ServerRequestInterface $request, ResponseInterface $response, int $id): ResponseInterface {
         $this->companyRepository->delete($id);
    
        return $response->withStatus(204);
    }

    #[PutRoute('/{id}')]
    public function editCompany(ServerRequestInterface $request,ResponseInterface $response, int $id): ResponseInterface {
        $data = $request->getParsedBody();
    
        $validator = new CompanyRequestValidator($request);
        $result = $validator->validated($data);
        
        if($result->fails()) {
            return $response->withJson($result->errors()->toArray(), 409);
        }
    
        $company = $this->companyRepository->update($id, new CreateUpdateCompanyDto($data['name']));
    
        return $response->withJson((new CompanyResource($company))->toArray($request), 200);
    }
}