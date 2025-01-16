<?php

namespace App\DataFixtures;

use App\Entity\Doctor;
use App\Entity\HealthcareCenter;
use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class DoctorFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(private SluggerInterface $slugger) {}

    public function load(ObjectManager $manager): void
    {
        $jack = new Doctor();
        $jack->setFirstName('Jack');
        $jack->setLastName('Smith');
        $jack->setPhoto('doctor-1.jpg');
        $jack->addSkill($this->getReference('skill_homeopathe', Skill::class));
        $jack->addSkill($this->getReference('skill_osteopathe', Skill::class));
        $jack->setHealthcareCenter($this->getReference('healthcare_center_acacias', HealthcareCenter::class));
        $manager->persist($jack);
        $this->addReference('doctor_' . strtolower($this->slugger->slug('Jack Smith')), $jack);

        $norma = new Doctor();
        $norma->setFirstName('Norma');
        $norma->setLastName('Pedric');
        $norma->setPhoto('doctor-2.jpg');
        $norma->addSkill($this->getReference('skill_medecin-generaliste', Skill::class));
        $norma->setHealthcareCenter($this->getReference('healthcare_center_acacias', HealthcareCenter::class));
        $manager->persist($norma);
        $this->addReference('doctor_' . strtolower($this->slugger->slug('Norma Pedric')), $norma);

        $maria = new Doctor();
        $maria->setFirstName('Maria');
        $maria->setLastName('Martin');
        $maria->setPhoto('doctor-3.jpg');
        $maria->addSkill($this->getReference('skill_dentiste', Skill::class));
        $maria->setHealthcareCenter($this->getReference('healthcare_center_acacias', HealthcareCenter::class));
        $manager->persist($maria);
        $this->addReference('doctor_' . strtolower($this->slugger->slug('Maria Martin')), $maria);

        $john = new Doctor();
        $john->setFirstName('John');
        $john->setLastName('Doe');
        $john->addSkill($this->getReference('skill_medecin-generaliste', Skill::class));
        $john->setHealthcareCenter($this->getReference('healthcare_center_bellecour', HealthcareCenter::class));
        $manager->persist($john);
        $this->addReference('doctor_' . strtolower($this->slugger->slug('John Doe')), $john);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SkillFixtures::class,
            HealthcareCenterFixtures::class,
        ];
    }
}