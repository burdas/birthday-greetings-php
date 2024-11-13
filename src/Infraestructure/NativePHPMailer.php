<?php


declare(strict_types=1);

namespace BirthdayGreetingsKata\Infraestructure;

use BirthdayGreetingsKata\Domain\Mailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class NativePHPMailer implements Mailer
{
    function sendMail(string $smtpHost, int $smtpPort, string $sender, string $subject, string $body, string $recipient): bool
    {
        $mail = new PHPMailer();

        try {
            $mail->isSMTP();
            $mail->Host = $smtpHost;
            $mail->SMTPAuth = false;
            $mail->Port = $smtpPort;

            $mail->setFrom($sender);
            $mail->addAddress($recipient);

            $mail->Subject = $subject;
            $mail->Body    = $body;

            return $mail->send();
        } catch (Exception $e) {
            print($e->getMessage());
            return false;
        }
    }
}