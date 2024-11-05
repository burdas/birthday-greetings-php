<?php


declare(strict_types=1);

namespace BirthdayGreetingsKata\Domain;

interface EmployeeRepository
{
    function getByBirthday(XDate $birthday): ?Employee;
}
