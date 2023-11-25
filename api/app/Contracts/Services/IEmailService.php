<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Contracts\Services;

use Psr\Http\Message\ServerRequestInterface;

interface IEmailService
{
    public function sendPasswordRecovery(string $emailAddress, string $subject, ServerRequestInterface $request, string $token): void;
    public function sendNewCreatedUser(string $emailAddress, string $password, ServerRequestInterface $request): void;
}