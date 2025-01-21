<?php

namespace App\Notification;

use Symfony\Component\Mailer\MailerInterface;

use Symfony\Component\Mime\Email;

/**
 * This is class trying to adapt Notification class initially designed for phone api.
 */
readonly class EmailNotification implements Notification
{

    public function __construct(
        private MailerInterface $mailer,
        private string $destinationEmail
    ) {
    }


    public function send(string $content): void
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to($this->destinationEmail)
            ->subject('Hello '. $this->destinationEmail.' here is message for you')
            ->text($content);

        $this->mailer->send($email);
    }
}
