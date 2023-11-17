<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use SchedulingTerms\App\Contracts\Repositories\TermsRepositoryContract;
use SchedulingTerms\App\Core\Routing\Attributes\DeleteRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GroupRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;
use SchedulingTerms\App\Dto\Terms\CreateUpdateTermDto;
use SchedulingTerms\App\Http\Resources\Terms\TermResource;
use SchedulingTerms\App\Http\Validators\Terms\TermRequestValidator;
use SchedulingTerms\App\Models\Term;

#[GroupRoute('/terms')]
class TermController
{
    public function __construct(
        public TermsRepositoryContract $termRepository
    )
    {
    }
    #[GetRoute('/paginate/{cursor}')]
    public function getTerms(Request $request, ResponseInterface $response, string $cursor)
    {
    
    }
    
    #[GetRoute('/{id}')]
    public function getTerm(Request $request, ResponseInterface $response, int $id)
    {
        // TODO: check permission
        /** @var Term $term */
        $term = $this->termRepository->get($id);
    
        return $response->withJson((new TermResource($term))->toArray($request), 200);
    }
    
    #[PostRoute('')]
    public function createTerm(ServerRequestInterface $request, ResponseInterface $response)
    {
        // TODO: check permission
        $data = $request->getParsedBody();
    
        $validator = new TermRequestValidator($request);
        $result = $validator->validated($data);
    
        if($result->fails()) {
            return $response->withJson($result->errors()->toArray()->toArray(), 409);
        }
    
        $term = $this->termRepository->create(new CreateUpdateTermDto(
            $data['user_id'],
            $data['job_id'],
            $data['company_id'],
            $data['start_date']
        ));
    
        return $response->withJson((new TermResource($term))->toArray($request), 201);
    }
    
    #[DeleteRoute('/{id}')]
    public function deleteTerm(Request $request, ResponseInterface $response, int $id)
    {
        $this->termRepository->delete($id);
    
        return $response->withStatus(204);
    }
    
    #[PutRoute('/{id}')]
    public function editTerm(Request $request, ResponseInterface $response, int $id)
    {
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
    
        return $response->withJson((new TermResource($term))->toArray($request), 200);
    }
}