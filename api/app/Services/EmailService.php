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
    
        $htmlTemplate = str_replace('{email}', $emailAddress, $htmlTemplate);
    
        $url = $this->getUrl($request, '/change-password/' . $token);
    
        $htmlTemplate = str_replace('{url}', $url , $htmlTemplate);
        
        $this->send($emailAddress, $subject, $htmlTemplate);
    }
    
    /**
     * @throws TransportExceptionInterface
     */
    public function sendNewCreatedUser(string $emailAddress, string $password, ServerRequestInterface $request): void
    {
        $htmlTemplate = file_get_contents(implode(DIRECTORY_SEPARATOR, [APP_BASE_PATH, 'resources', 'templates', 'new-user.html']));
    
        $htmlTemplate = str_replace('{email}', $emailAddress, $htmlTemplate);
        $htmlTemplate = str_replace('{password}', $password, $htmlTemplate);
        
        $this->send($emailAddress, "New User", $htmlTemplate);
    }
    
    private function getUrl(ServerRequestInterface $request, string $path): string
    {
        $uri = $request->getUri();
        return $uri->getScheme() . '://' . $uri->getHost() . $path;
    }
}