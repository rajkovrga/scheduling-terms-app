<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Contracts\Services;

interface IEmailService
{
    public function send(string $emailAddress, string $subject, string $message): void;
}