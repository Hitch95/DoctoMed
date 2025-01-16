<?php

namespace App\DataFixtures;

use App\Entity\HealthcareCenter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HealthcareCenterFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $healthcareCenterAcacias = new HealthcareCenter();
        $healthcareCenterAcacias->setName('Cabinet Médical des Acacias');
        $healthcareCenterAcacias->setPhone('01 23 45 67 89');
        $healthcareCenterAcacias->setPhoneEmergency('06 12 34 56 78');
        $healthcareCenterAcacias->setEmail('contact@cabinet-acacias.fr');
        $manager->persist($healthcareCenterAcacias);
        $this->addReference('healthcare_center_acacias', $healthcareCenterAcacias);

        $healthcareCenterBellecour = new HealthcareCenter();
        $healthcareCenterBellecour->setName('Cabinet Médical Bellecour');
        $healthcareCenterBellecour->setPhone('01 45 67 89 01');
        $healthcareCenterBellecour->setPhoneEmergency('06 55 44 33 22');
        $healthcareCenterBellecour->setEmail('contact@cabinet-bellecour.fr');
        $manager->persist($healthcareCenterBellecour);
        $this->addReference('healthcare_center_bellecour', $healthcareCenterBellecour);

        $manager->flush();
    }
}