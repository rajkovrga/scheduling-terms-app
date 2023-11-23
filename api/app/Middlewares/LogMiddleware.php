<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Http\ServerRequest;

class LogMiddleware
{
    public function __invoke(ServerRequest $request, RequestHandler $handler): ResponseInterface
    {
        return $handler->handle($request);
    }
}