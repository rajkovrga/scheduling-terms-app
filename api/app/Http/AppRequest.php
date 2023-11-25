<?php

namespace SchedulingTerms\App\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SchedulingTerms\App\Exceptions\PermissionDeniedException;
use SchedulingTerms\App\Models\User;
use SchedulingTerms\App\Utils\CurrentUser;
use Slim\Http\ServerRequest;
use Slim\Psr7\Request;

class AppRequest extends ServerRequest implements ServerRequestInterface {

    public ?User $currentUser = null;
    public array $permissions = [];
    public string $token;
    public function user(): ?User
    {
        return $this->currentUser;
    }
    
    /**
     * @throws PermissionDeniedException
     */
    public function checkPermission(string $permission, ResponseInterface $response): void {
        if(in_array($permission, $this->permissions)) {
            throw new PermissionDeniedException();
        }
    }
    public function can(string $permission): bool {
        return in_array($permission, $this->permissions);
    }
}