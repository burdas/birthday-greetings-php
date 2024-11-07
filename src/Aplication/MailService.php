<?php


namespace BirthdayGreetingsKata\Aplication;

use BirthdayGreetingsKata\Domain\Employee;
use BirthdayGreetingsKata\Domain\Mailer;

class MailService
{
    private Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendBirthdayGreetings(Employee $employee, string $smtpHost, int $smtpPort): void
    {
        $recipient = $employee->getEmail();
        $body = sprintf('Happy Birthday, dear %s!', $employee->getFirstName());
        $subject = 'Happy Birthday!';
        $this->mailer->sendMail($smtpHost, $smtpPort, $this->mailer::SENDER_EMAIL, $subject, $body, $recipient);

    }
}