<?php

namespace App\Tests;

use App\Entity\Doctor;
use PHPUnit\Framework\TestCase;

class DoctorTest extends TestCase
{
    public function testFullName(): void
    {
        $doctor = new Doctor();
        $doctor->setFirstName('John');
        $doctor->setLastName('Doe');

        $this->assertEquals('John DOE', $doctor->getFullName());
    }

    public function testFullNameUppercase(): void
    {
        $doctor = new Doctor();
        $doctor->setFirstName('John');
        $doctor->setLastName('doe');

        $this->assertEquals('John DOE', $doctor->getFullName());
    }
}
