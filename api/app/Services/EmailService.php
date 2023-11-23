<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Services;

use Psr\Http\Message\ServerRequestInterface;
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
    protected function send(string $emailAddress, string $subject, string $message): void
    {
        
        $email = (new Email())
            ->from($this->config->get('mail.smtp.email'))
            ->to($emailAddress)
            ->subject($subject)
            ->html($message);

            $this->mailer->send($email);
    }
    
    /**
     * @throws TransportExceptionInterface
     */
    public function sendPasswordRecovery(string $emailAddress, string $subject, ServerRequestInterface $request, string $token): void
    {
        $htmlTemplate = file_get_contents(implode(DIRECTORY_SEPARATOR, [APP_BASE_PATH, 'resources', 'templates', 'recovery-password.html']));
        $uri = $request->getUri();
    
        $htmlTemplate = str_replace('{email}', $emailAddress, $htmlTemplate);
    
        $url = $uri->getScheme() . '://' . $uri->getHost() . '/change-password/' . $token;
    
        $htmlTemplate = str_replace('{url}', $url , $htmlTemplate);
        
        $this->send($emailAddress, $subject, $htmlTemplate);
    }
}