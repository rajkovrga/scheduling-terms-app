<?php

namespace SchedulingTerms\App\Http;

use Psr\Http\Message\ServerRequestInterface;
use SchedulingTerms\App\Models\User;
use SchedulingTerms\App\Utils\CurrentUser;
use Slim\Http\ServerRequest;
use Slim\Psr7\Request;

class AppRequest extends ServerRequest implements ServerRequestInterface {

    public ?User $currentUser = null;
    public function user(): ?User
    {
        return $this->currentUser;
    }

}