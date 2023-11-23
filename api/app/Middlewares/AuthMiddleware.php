<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use SchedulingTerms\App\Contracts\Repositories\AuthRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\TokenRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\UserRepositoryContract;
use SchedulingTerms\App\Exceptions\AuthException;
use SchedulingTerms\App\Http\AppRequest;
use SchedulingTerms\App\Models\User;
use SchedulingTerms\App\Utils\CurrentUser;
use Slim\Http\ServerRequest;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

readonly class AuthMiddleware
{
    public function __construct(
        private TokenRepositoryContract $tokenRepository,
        private UserRepositoryContract  $userRepository
    )
    {
    }

    /**
     * @throws AuthException
     */
    public function __invoke(ServerRequest $request, RequestHandler $handler): ResponseInterface
    {
        $token = $request->getHeaderLine('Authorization');

        if(!$token) {
            throw new AuthException('User is not authorized');
        }

        $data = $this->tokenRepository->get($token);

        /** @var User $user */
        $user = $this->userRepository->get($data->id);
        $req = new AppRequest($request);
    
        $req->currentUser = $user;
        $req->token = $token;
    
        return $handler->handle($req);
    }
}