<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rakit\Validation\RuleQuashException;
use SchedulingTerms\App\Contracts\Repositories\TermsRepositoryContract;
use SchedulingTerms\App\Core\Routing\Attributes\DeleteRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GroupRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;
use SchedulingTerms\App\Dto\Terms\CreateUpdateTermDto;
use SchedulingTerms\App\Exceptions\PermissionDeniedException;
use SchedulingTerms\App\Http\AppRequest;
use SchedulingTerms\App\Http\Resources\Pagination\PaginationResource;
use SchedulingTerms\App\Http\Resources\Terms\TermResource;
use SchedulingTerms\App\Http\Validators\Terms\TermRequestValidator;
use SchedulingTerms\App\Models\Term;
use SchedulingTerms\App\Utils\Permissions;

#[GroupRoute('/terms', ['auth'])]
readonly class TermController
{
    public function __construct(
        public TermsRepositoryContract $termRepository
    )
    {
    }
    #[GetRoute('/paginate/{cursor:0}',  ['auth'])]
    public function getTerms(AppRequest $request, ResponseInterface $response, int $cursor = 0)
    {
        if($request->can(Permissions::ViewTerms)) {
            $data = $this->termRepository->paginate($cursor);
        }
    
        if($request->can(Permissions::ViewSelfTerms)) {
            $data = $this->termRepository->paginateByCompanyId($cursor);
        }
    
        return $response->withJson((new PaginationResource($request))->toArray((new TermResource($request))->toCollection($data)),200);
    }
    
    /**
     * @throws PermissionDeniedException
     */
    #[GetRoute('/{id}')]
    public function getTerm(AppRequest $request, ResponseInterface $response, int $id)
    {
        if(!$request->can(Permissions::ViewTerms) || $id !== $request->user()->company->id) {
            throw new PermissionDeniedException();
        }
        $term = $this->termRepository->get($id);
    
        return $response->withJson((new TermResource($request))->toArray($term), 200);
    }
    
    /**
     * @throws RuleQuashException
     * @throws PermissionDeniedException
     */
    #[PostRoute('')]
    public function createTerm(AppRequest $request, ResponseInterface $response)
    {
        $request->checkPermission(Permissions::CreateTerm, $response);
        
        $data = $request->getParsedBody();
    
        $validator = new TermRequestValidator($request);
        $result = $validator->validated($data);
    
        if($result->fails()) {
            return $response->withJson($result->errors()->toArray(), 409);
        }
    
        $term = $this->termRepository->create(new CreateUpdateTermDto(
            $data['user_id'],
            $data['job_id'],
            $data['company_id'],
            $data['start_date']
        ));
    
        return $response->withJson((new TermResource($request))->toArray($term), 201);
    }
    
    /**
     * @throws PermissionDeniedException
     */
    #[DeleteRoute('/{id}')]
    public function deleteTerm(AppRequest $request, ResponseInterface $response, int $id): ResponseInterface
    {
        $request->checkPermission(Permissions::DeleteTerm, $response);
    
        $this->termRepository->delete($id);
    
        return $response->withStatus(204);
    }
    
    /**
     * @throws RuleQuashException
     * @throws PermissionDeniedException
     */
    #[PutRoute('/{id}')]
    public function editTerm(AppRequest $request, ResponseInterface $response, int $id)
    {
        $request->checkPermission(Permissions::EditTerm, $response);
    
        $data = $request->getParsedBody();
    
        $validator = new TermRequestValidator($request);
        $result = $validator->validated($data);
    
        if($result->fails()) {
            return $response->withJson($result->errors()->toArray(), 409);
        }
    
        $term = $this->termRepository->update($id, new CreateUpdateTermDto(
            $data['user_id'],
            $data['job_id'],
            $data['company_id'],
            $data['start_date']
        ));
    
        return $response->withJson((new TermResource($request))->toArray($term), 200);
    }
}