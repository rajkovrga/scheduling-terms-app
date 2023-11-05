<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Services;

use SchedulingTerms\App\Contracts\Services\IEmailService;
use SchedulingTerms\App\Utils\Config;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

readonly class EmailService implements IEmailService
{
    public function __construct(
        private Mailer $mailer,
        private Config $config
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(string $emailAddress, string $subject, string $message): void
    {
        
        $email = (new Email())
            ->from($this->config->get('mail.smtp.email'))
            ->to($emailAddress)
            ->subject($subject)
            ->html($message);

            $this->mailer->send($email);
    }
}