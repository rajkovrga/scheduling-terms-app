<?php

namespace SchedulingTerms\App\Http;

use Psr\Http\Message\ServerRequestInterface;
use SchedulingTerms\App\Utils\CurrentUser;
use Slim\Psr7\Request;

class AppRequest extends Request implements ServerRequestInterface {

    public function user(): ?CurrentUser
    {
        $user = $this->getAttribute('user');

        if($user) {
            return $user instanceof CurrentUser ? $user : null;
        }

        return null;
    }

}