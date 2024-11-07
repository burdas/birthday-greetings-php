<?php

namespace BirthdayGreetingsKata\Aplication;

use BirthdayGreetingsKata\Domain\EmployeeRepository;
use BirthdayGreetingsKata\Domain\XDate;
class BirthdayService
{
    private EmployeeRepository $employeeRepository;
    private MailService $mailService;

    public function __construct(EmployeeRepository $employeeRepository, MailService $mailService)
    {
        $this->employeeRepository = $employeeRepository;
        $this->mailService = $mailService;
    }

    public function sendGreetings(XDate $birthday, string $smtpHost, int $smtpPort): void
    {
        $birthdayEmployees = $this->employeeRepository->getByBirthday($birthday);
        foreach ($birthdayEmployees as $employee) {
            $this->mailService->sendBirthdayGreetings($employee, $smtpHost, $smtpPort);
        }
    }
}