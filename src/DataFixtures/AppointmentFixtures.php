<?php

namespace App\DataFixtures;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\HealthcareCenter;
use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppointmentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $yesterdayMorning = new \DateTimeImmutable('yesterday 09:00');
        $tomorrowMorning = new \DateTimeImmutable('tomorrow 09:00');
        $tomorrowAfternoon = new \DateTimeImmutable('tomorrow 14:00');

        $appointment1 = new Appointment();
        $appointment1->setStartAt($tomorrowMorning);
        $appointment1->setEndAt($tomorrowMorning->modify('+30 minutes'));
        $appointment1->setDoctor($this->getReference('doctor_jack-smith', Doctor::class));
        $appointment1->setSkill($this->getReference('skill_homeopathe', Skill::class));
        $appointment1->setFirstName('Frank');
        $appointment1->setLastName('Doe');
        $appointment1->setEmail('franck.doe@gmail.com');
        $appointment1->setPhone('06 12 34 56 78');
        $appointment1->setHealthcareCenter($this->getReference('healthcare_center_acacias', HealthcareCenter::class));
        $manager->persist($appointment1);

        $appointment2 = new Appointment();
        $appointment2->setStartAt($yesterdayMorning);
        $appointment2->setEndAt($yesterdayMorning->modify('+30 minutes'));
        $appointment2->setDoctor($this->getReference('doctor_norma-pedric', Doctor::class));
        $appointment2->setSkill($this->getReference('skill_medecin-generaliste', Skill::class));
        $appointment2->setFirstName('Alice');
        $appointment2->setLastName('Doe');
        $appointment2->setEmail('alide.doe@gmail.com');
        $appointment2->setPhone('06 12 34 44 78');
        $appointment2->setHealthcareCenter($this->getReference('healthcare_center_acacias', HealthcareCenter::class));
        $manager->persist($appointment2);

        $appointment3 = new Appointment();
        $appointment3->setStartAt($tomorrowAfternoon);
        $appointment3->setEndAt($tomorrowAfternoon->modify('+30 minutes'));
        $appointment3->setSkill($this->getReference('skill_dentiste', Skill::class));
        $appointment3->setFirstName('Bob');
        $appointment3->setLastName('Doe');
        $appointment3->setEmail('bob.doe@gmail.com');
        $appointment3->setPhone('06 12 34 55 78');
        $appointment3->setHealthcareCenter($this->getReference('healthcare_center_acacias', HealthcareCenter::class));
        $manager->persist($appointment3);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DoctorFixtures::class,
            SkillFixtures::class,
            HealthcareCenterFixtures::class,
        ];
    }
}