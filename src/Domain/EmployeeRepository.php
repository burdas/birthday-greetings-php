<?php


declare(strict_types=1);

namespace BirthdayGreetingsKata\Domain;

interface EmployeeRepository
{
    /**
     * @return Employee[]
    */
    function getByBirthday(XDate $birthday): array;
}
