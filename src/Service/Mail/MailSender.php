<?php

namespace App\Service\Mail;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailSender
{
    private MailerInterface $mailer;
    private string $mailFrom;

    public function __construct(MailerInterface $mailer, string $mailFrom)
    {
        $this->mailer = $mailer;
        $this->mailFrom = $mailFrom;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendMail(
        string $email,
        string $subject,
        string $message,
        mixed $attach = null,
        ?string $title = null
    ): void {
        $this->mailer->send((new Email())
            ->from($this->mailFrom)
            ->to($email)
            ->subject($subject)
            ->text($message)
            ->attach($attach, sprintf('%s.pdf', $title ?? 'file')));
    }
}
