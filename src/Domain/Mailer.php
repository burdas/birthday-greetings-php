<?php


declare(strict_types=1);

namespace BirthdayGreetingsKata\Domain;

interface Mailer
{
    const SENDER_EMAIL = 'sender@here.com';
    function sendMail(string $smtpHost, int $smtpPort, string $sender, string $subject, string $body, string $recipient): int;
}