<?php

namespace SchedulingTerms\App\Middlewares;

use Cake\Http\Client\Exception\RequestException;
use Cake\Http\Exception\InvalidCsrfTokenException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use SchedulingTerms\App\Contracts\Repositories\TokenRepositoryContract;
use Slim\Http\ServerRequest;

class CsrfMiddleware
{
    
    public function __construct(
        private TokenRepositoryContract $tokenRepository,
    )
    {
    }
    public function __invoke(ServerRequest $request, RequestHandler $handler): ResponseInterface
    {
        $token = $request->getHeaderLine('Token');
        
        $result = $this->tokenRepository->checkCsrf($token);
        
        if(!$result && $request->getUri()->getPath() !== '/csrf') {
            throw new RequestException('Request is not valid', $request);
        }
        
        $this->tokenRepository->deleteCsrf($token);
        
        return $handler->handle($request);
    }
}