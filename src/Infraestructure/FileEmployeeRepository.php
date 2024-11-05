<?php


declare(strict_types=1);

namespace BirthdayGreetingsKata\Infraestructure;

use BirthdayGreetingsKata\Domain\Employee;
use BirthdayGreetingsKata\Domain\EmployeeRepository;
use BirthdayGreetingsKata\Domain\XDate;

class FileEmployeeRepository implements EmployeeRepository
{
    const FILE_NAME = __DIR__ . '/resources/employee_data.txt';
    function getByBirthday(XDate $birthday): array {

        $fileHandler = fopen($this::FILE_NAME, 'r');

        $birthdayEmployees = [];
        while ($employeeData = fgetcsv($fileHandler, null, ',')) {
            $employeeData = array_map('trim', $employeeData);
            $employee = new Employee($employeeData[1], $employeeData[0], $employeeData[2], $employeeData[3]);
            if ($employee->isBirthday($birthday)) {
                $birthdayEmployees[] = $employee;
            }
        }
        return $birthdayEmployees;
    }
}