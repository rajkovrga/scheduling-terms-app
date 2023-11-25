<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rakit\Validation\RuleQuashException;
use SchedulingTerms\App\Contracts\Repositories\CompanyRepositoryContract;
use SchedulingTerms\App\Core\Routing\Attributes\DeleteRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GroupRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;
use SchedulingTerms\App\Dto\Companies\CreateUpdateCompanyDto;
use SchedulingTerms\App\Exceptions\PermissionDeniedException;
use SchedulingTerms\App\Http\AppRequest;
use SchedulingTerms\App\Http\Resources\Companies\CompanyResource;
use SchedulingTerms\App\Http\Resources\Pagination\PaginationResource;
use SchedulingTerms\App\Http\Validators\Companies\CompanyRequestValidator;
use SchedulingTerms\App\Models\Company;
use SchedulingTerms\App\Utils\Permissions;

#[GroupRoute('/companies', ['auth'])]
readonly class CompanyController
{
    public function __construct(
        public CompanyRepositoryContract $companyRepository
    )
    {
    }
    
    /**
     * @throws PermissionDeniedException
     */
    #[GetRoute('/paginate/{cursor:0}')]
    public function getCompanies(AppRequest $request,ResponseInterface $response, int $cursor = 0): ResponseInterface {
        $request->checkPermission(Permissions::ViewAllCompanies, $response);
    
        $data = $this->companyRepository->paginate($cursor);

        return $response->withJson((new PaginationResource($request))->toArray((new CompanyResource($request))->toCollection($data)),200);
    }
    
    /**
     * @throws PermissionDeniedException
     */
    #[GetRoute('/{id}')]
    public function getCompany(AppRequest $request,ResponseInterface $response, int $id): ResponseInterface {
        if(!$request->can(Permissions::ViewAllCompanies) || $id !== $request->user()->company->id) {
            throw new PermissionDeniedException();
        }
        
        $company = $this->companyRepository->get($id);
    
        return $response->withJson((new CompanyResource($request))->toArray($company), 200);
    }
    
    /**
     * @throws RuleQuashException
     * @throws PermissionDeniedException
     */
    #[PostRoute('')]
    public function createCompany(AppRequest $request, ResponseInterface $response): ResponseInterface {
        $request->checkPermission(Permissions::CreateCompany, $response);
    
        $data = $request->getParsedBody();
        
        $validator = new CompanyRequestValidator($request);
        $result = $validator->validated($data);
        
        if($result->fails()) {
            return $response->withJson($result->errors()->toArray(), 409);
        }
        
        $company = $this->companyRepository->create(new CreateUpdateCompanyDto($data['name']));
        
        return $response->withJson((new CompanyResource($request))->toArray($company), 201);
    }
    
    /**
     * @throws PermissionDeniedException
     */
    #[DeleteRoute('/{id}')]
    public function deleteCompany(AppRequest $request, ResponseInterface $response, int $id): ResponseInterface {
        $request->checkPermission(Permissions::DeleteCompany, $response);
    
        $this->companyRepository->delete($id);
    
        return $response->withStatus(204);
    }
    
    /**
     * @throws RuleQuashException
     * @throws PermissionDeniedException
     */
    #[PutRoute('/{id}')]
    public function editCompany(AppRequest $request,ResponseInterface $response, int $id): ResponseInterface {
        $request->checkPermission(Permissions::EditCompany, $response);
        $request->checkPermission(Permissions::EditSelfCompany, $response);
    
        $data = $request->getParsedBody();
    
        $validator = new CompanyRequestValidator($request);
        $result = $validator->validated($data);
        
        if($result->fails()) {
            return $response->withJson($result->errors()->toArray(), 409);
        }
    
        $company = $this->companyRepository->update($id, new CreateUpdateCompanyDto($data['name']));
    
        return $response->withJson((new CompanyResource($request))->toArray($company), 200);
    }
}