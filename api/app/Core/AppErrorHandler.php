<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Core;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use SchedulingTerms\App\Exceptions\AuthException;
use SchedulingTerms\App\Exceptions\ModelNotFoundException;
use SchedulingTerms\App\Exceptions\PermissionDeniedException;
use SchedulingTerms\App\Exceptions\TokenAuthException;
use Slim\Handlers\ErrorHandler;
use Slim\Interfaces\CallableResolverInterface;

class AppErrorHandler extends ErrorHandler
{
    protected array $exceptions = [];
    public function __construct(CallableResolverInterface $callableResolver, ResponseFactoryInterface $responseFactory, ?LoggerInterface $logger = null)
    {
        parent::__construct($callableResolver, $responseFactory, $logger);
        $this->defaultErrorRendererContentType = 'application/json';
    }

    protected function determineStatusCode(): int
    {
        $code = parent::determineStatusCode();

        return match ($this->exception::class) {
            ModelNotFoundException::class => 404,
            TokenAuthException::class,
            PermissionDeniedException::class => 403,
            AuthException::class => 401,
            default => 500
        };
    }

    protected function determineContentType(ServerRequestInterface $request): ?string
    {
        if ($this->contentType !== null) {
            return $this->contentType;
        }

        return $this->defaultErrorRendererContentType;
    }
    protected function logError(string $error): void
    {
        if ($this->statusCode <= 500) {
            $this->logger->warning(
                $this->exception->getMessage(),
                [
                    'exception' => get_class($this->exception),
                    'line' => $this->exception->getLine(),
                    'code' => $this->exception->getCode(),
                    'ip' => $this->request->getAttribute('ip_address'),
                    'method' => $this->request->getMethod(),
                    'path' => $this->request->getUri()->getPath(),
                    'body' => $this->request->getParsedBody()
                ]);

        }
    }

    protected function respond(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($this->statusCode);
        
        $renderer = $this->determineRenderer();
        $body = $renderer($this->exception, $this->displayErrorDetails);
        $response->getBody()->write($body);

        return $response;
    }

}