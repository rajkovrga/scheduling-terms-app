<?php

namespace SchedulingTerms\App\Http\Validators;

use Psr\Http\Message\ServerRequestInterface;
use SchedulingTerms\App\Models\User;
use SchedulingTerms\App\Utils\CurrentUser;
use Slim\Http\ServerRequest;

class AuthRequest extends ServerRequest implements ServerRequestInterface
{
    public function user(): ?CurrentUser
    {
        $user = $this->serverRequest->getAttribute('user');

        if($user) {
            return $user instanceof CurrentUser ? $user : null;
        }

        return null;
    }


}