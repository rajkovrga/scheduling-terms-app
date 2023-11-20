<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Controllers;

use Cake\Database\Connection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rakit\Validation\RuleQuashException;
use Redis;
use SchedulingTerms\App\Contracts\Repositories\TokenRepositoryContract;
use SchedulingTerms\App\Contracts\Services\IEmailService;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;
use SchedulingTerms\App\Dto\Pagination\PaginateDto;
use SchedulingTerms\App\Http\Validators\Auth\LoginValidator;
use SchedulingTerms\App\Http\Validators\Companies\CompanyRequestValidator;
use SchedulingTerms\App\Services\EmailService;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

readonly class AuthController
{
    public function __construct(
        private IEmailService   $emailService,
        private TokenRepositoryContract $tokenRepositoryContract
    ) {
    }

    /**
     * @throws RuleQuashException
     */
    #[PostRoute('/login')]
    public function login(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();

        $validator = new CompanyRequestValidator($request);
        $result = $validator->validated($data);

        if($result->fails()) {
            return $response->withJson($result->errors()->toArray(), 409);
        }

        

        $this->emailService->send();

        return $response->withStatus('')->withJson([]);
    }
    
    /**
     * @throws TransportExceptionInterface
     */
    #[GetRoute('/me')]
    public function me(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        
        return $response->withJson([], 201);
    }

    #[PostRoute('/forget-password/{token}')]
    public function forgetPassword(ServerRequestInterface $request, ResponseInterface $response, string $token): ResponseInterface
    {
    
        $this->emailService->send("rajkovrga.it@gmail.com", 'test', '<h1>test</h1>');
        return $response->withJson([], 201);
    }

    #[PutRoute('/change-password')]
    public function changePassword(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
    
        return $response->withJson([], 204);
    }

}