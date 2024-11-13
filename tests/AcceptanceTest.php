<?php

declare(strict_types=1);

namespace Tests\BirthdayGreetingsKata;

use BirthdayGreetingsKata\Aplication\BirthdayService;
use BirthdayGreetingsKata\Aplication\MailService;
use BirthdayGreetingsKata\Domain\XDate;
use BirthdayGreetingsKata\Infraestructure\FileEmployeeRepository;
use BirthdayGreetingsKata\Infraestructure\NativePHPMailer;
use BirthdayGreetingsKata\Infraestructure\SwiftMailer;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class AcceptanceTest extends TestCase
{
    private const SMTP_HOST = '127.0.0.1';
    private const SMTP_PORT = 1025;

    /**
     * @var BirthdayService
     */
    private $service;

    /** @before */
    protected function startMailhog(): void
    {
        Process::fromShellCommandline('docker-compose up -d')->run();
        Process::fromShellCommandline('docker-compose ps')->run();

        $mailService =  new MailService(new NativePHPMailer());
        $fileEmployeeRepository = new FileEmployeeRepository();
        $this->service = new BirthdayService($fileEmployeeRepository, $mailService);
    }

    protected function oldStartMailhog(): void
    {
        $whichDockerCompose = Process::fromShellCommandline('which docker-compose');
        $whichDockerCompose->run();

        if ('' === $whichDockerCompose->getOutput()) {
            $this->markTestSkipped('To run this test you should have docker-compose installed.');
        }

        Process::fromShellCommandline('docker stop $(docker ps -a)')->run();
        Process::fromShellCommandline('docker-compose up -d')->run();

        $mailService =  new MailService(new NativePHPMailer());
        $fileEmployeeRepository = new FileEmployeeRepository();
        $this->service = new BirthdayService($fileEmployeeRepository, $mailService);
    }

    /** @after */
    protected function stopMailhog(): void
    {
        (new Client())->delete('http://127.0.0.1:8025/api/v1/messages');
        Process::fromShellCommandline('docker-compose down')->run();
    }

    protected function oldStopMailhog(): void
    {
        (new Client())->delete('http://127.0.0.1:8025/api/v1/messages');
        Process::fromShellCommandline('docker-compose stop')->run();
        Process::fromShellCommandline('docker-compose rm -f')->run();
    }

    /**
     * @test
     */
    public function willSendGreetings_whenItsSomebodysBirthday(): void
    {
        $this->service->sendGreetings(
            new XDate('2008/10/08'),
            static::SMTP_HOST,
            static::SMTP_PORT
        );

        $messages = $this->messagesSent();
        $this->assertCount(1, $messages, 'message not sent?');

        $message = $messages[0];
        $this->assertEquals('Happy Birthday, dear John!', trim($message['Content']['Body']));
        $this->assertEquals('Happy Birthday!', $message['Content']['Headers']['Subject'][0]);
        $this->assertCount(1, $message['Content']['Headers']['To']);
        $this->assertEquals('john.doe@foobar.com', $message['Content']['Headers']['To'][0]);
    }

    /**
     * @test
     */
    public function willNotSendEmailsWhenNobodysBirthday(): void
    {
        $this->service->sendGreetings(
            new XDate('2008/01/01'),
            static::SMTP_HOST,
            static::SMTP_PORT
        );

        $this->assertCount(0, $this->messagesSent(), 'what? messages?');
    }

    private function messagesSent(): array
    {
        return json_decode(file_get_contents('http://127.0.0.1:8025/api/v1/messages'), true);
    }
}
