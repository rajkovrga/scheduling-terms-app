<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use SchedulingTerms\App\Contracts\Repositories\AuthRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\TokenRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\UserRepositoryContract;
use SchedulingTerms\App\Exceptions\AuthException;
use SchedulingTerms\App\Models\User;
use SchedulingTerms\App\Utils\CurrentUser;

readonly class AuthMiddleware
{
    public function __construct(
        private TokenRepositoryContract $tokenRepository,
        private UserRepositoryContract  $userRepository,
        private AuthRepositoryContract  $authRepository
    )
    {
    }

    /**
     * Example middleware invokable class
     *
     * @param Request $request PSR-7 request
     * @param RequestHandler $handler PSR-15 request handler
     *
     * @return ResponseInterface
     * @throws AuthException
     */
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $token = $request->getAttribute('token');

        if(!$token) {
            throw new AuthException('User is not authorized');
        }

        $token = $this->tokenRepository->get($token);

        /** @var User $user */
        $user = $this->userRepository->get($token->id);

        $permissions = $this->authRepository->getPermissions($user->roleId);

        $request->withAttribute('user', new CurrentUser(
            $user,
            $permissions
        ));

        return $handler->handle($request);
    }
}