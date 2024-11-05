<?php


declare(strict_types=1);

namespace BirthdayGreetingsKata\Infraestructure;

use BirthdayGreetingsKata\Domain\Mailer;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class SwiftMailer implements Mailer
{
    function sendMail(string $smtpHost, int $smtpPort, string $sender, string $subject, string $body, string $recipient): int
    {
        // Create a mailer
        $mailer = new Swift_Mailer(
            new Swift_SmtpTransport($smtpHost, $smtpPort)
        );

        // Construct the message
        $msg = new Swift_Message($subject);
        $msg
            ->setFrom($sender)
            ->setTo([$recipient])
            ->setBody($body);

        // Send the message
        return $mailer->send($msg);
    }
}