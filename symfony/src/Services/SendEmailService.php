<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class SendEmailService
{


    public function __construct(private MailerInterface $mailer) {}

    public function send(
        string $from,
        string $to,
        string $subject,
        string $htmlContent,
        array $context
    ): void {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("emails/$htmlContent.html.twig")
            ->context($context);

        $this->mailer->send($email);
    }
}
