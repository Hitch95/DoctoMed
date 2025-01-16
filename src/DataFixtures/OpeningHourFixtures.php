<?php

namespace App\DataFixtures;

use App\Entity\HealthcareCenter;
use App\Entity\OpeningHour;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OpeningHourFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $monday = new OpeningHour();
        $monday->setWeekday('Lundi');
        $monday->setWeekdayNumber(1);
        $monday->setOpeningTime(new \DateTime('09:00:00'));
        $monday->setClosingTime(new \DateTime('17:00:00'));
        $monday->setHealthcareCenter($this->getReference('healthcare_center_acacias', HealthcareCenter::class));
        $manager->persist($monday);

        $tuesday = new OpeningHour();
        $tuesday->setWeekday('Mardi');
        $tuesday->setWeekdayNumber(2);
        $tuesday->setOpeningTime(new \DateTime('09:00:00'));
        $tuesday->setClosingTime(new \DateTime('17:00:00'));
        $tuesday->setHealthcareCenter($this->getReference('healthcare_center_acacias', HealthcareCenter::class));
        $manager->persist($tuesday);

        $wednesday = new OpeningHour();
        $wednesday->setWeekday('Mercredi');
        $wednesday->setWeekdayNumber(3);
        $wednesday->setOpeningTime(new \DateTime('09:00:00'));
        $wednesday->setClosingTime(new \DateTime('17:00:00'));
        $wednesday->setHealthcareCenter($this->getReference('healthcare_center_acacias', HealthcareCenter::class));
        $manager->persist($wednesday);

        $thursday = new OpeningHour();
        $thursday->setWeekday('Jeudi');
        $thursday->setWeekdayNumber(4);
        $thursday->setOpeningTime(new \DateTime('09:00:00'));
        $thursday->setClosingTime(new \DateTime('17:00:00'));
        $thursday->setHealthcareCenter($this->getReference('healthcare_center_acacias', HealthcareCenter::class));
        $manager->persist($thursday);

        $friday = new OpeningHour();
        $friday->setWeekday('Vendredi');
        $friday->setWeekdayNumber(5);
        $friday->setOpeningTime(new \DateTime('09:00:00'));
        $friday->setClosingTime(new \DateTime('17:00:00'));
        $friday->setHealthcareCenter($this->getReference('healthcare_center_acacias', HealthcareCenter::class));
        $manager->persist($friday);

        $saturday = new OpeningHour();
        $saturday->setWeekday('Samedi');
        $saturday->setWeekdayNumber(6);
        $saturday->setOpeningTime(new \DateTime('09:00:00'));
        $saturday->setClosingTime(new \DateTime('12:00:00'));
        $saturday->setHealthcareCenter($this->getReference('healthcare_center_acacias', HealthcareCenter::class));
        $manager->persist($saturday);

        $sunday = new OpeningHour();
        $sunday->setWeekday('Dimanche');
        $sunday->setWeekdayNumber(7);
        $sunday->setHealthcareCenter($this->getReference('healthcare_center_acacias', HealthcareCenter::class));
        $manager->persist($sunday);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            HealthcareCenterFixtures::class,
        ];
    }
}