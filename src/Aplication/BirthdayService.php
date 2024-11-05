<?php

namespace BirthdayGreetingsKata\Aplication;

use BirthdayGreetingsKata\Domain\EmployeeRepository;
use BirthdayGreetingsKata\Domain\XDate;
class BirthdayService
{
    protected EmployeeRepository $employeeRepository;

    public function __constructor(EmployeeRepository $employeeRepository): void
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function sendGreetings(XDate $birthday, ): void
    {
        $birthdayEmployees = $this->employeeRepository->getByBirthday($birthday);

        foreach ($birthdayEmployees as $employee) {

        }
    }
}