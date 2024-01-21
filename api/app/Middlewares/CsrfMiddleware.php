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
        if($request->getUri()->getPath() == '/csrf') {
            return $handler->handle($request);
        }
        
        $token = $request->getHeaderLine('X-XSRF-TOKEN');

//        if($token === '') {
//            throw new RequestException('Request is not valid aaaaa', $request);
//        }
//
        $result = $this->tokenRepository->checkCsrf($token);
    
        $this->tokenRepository->deleteCsrf($token);
    
//        if(!$result) {
//            throw new RequestException('Request is not valid', $request);
//        }

        return $handler->handle($request);
    }
}