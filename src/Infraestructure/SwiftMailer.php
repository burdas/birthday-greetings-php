<?php


declare(strict_types=1);

namespace BirthdayGreetingsKata\Infraestructure;

use BirthdayGreetingsKata\Domain\Mailer;
use Swift_Mailer;
use Swift_Message;
use Swift_Plugins_LoggerPlugin;
use Swift_Plugins_Loggers_EchoLogger;
use Swift_SmtpTransport;

class SwiftMailer implements Mailer
{
    function sendMail(string $smtpHost, int $smtpPort, string $sender, string $subject, string $body, string $recipient): int
    {
        // Create a mailer
        var_dump($smtpHost, $smtpPort);
        $transport = (new Swift_SmtpTransport('mailhog', 1025));
        $streamOptions = array(
            'socket' => array(
                'bindto' => '0:0' // Use 0:0 to avoid any conflicts
            ),
        );
        $transport->setStreamOptions($streamOptions);
        $mailer = new Swift_Mailer(
            $transport
        );

        // Construct the message
        $msg = new Swift_Message($subject);
        $msg
            ->setFrom($sender)
            ->setTo([$recipient])
            ->setBody($body);

        $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin(new Swift_Plugins_Loggers_EchoLogger()));

        // Send the message
        return $mailer->send($msg);
    }
}