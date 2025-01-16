<?php

namespace App\DataFixtures;

use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class SkillFixtures extends Fixture
{

    public function __construct(private SluggerInterface $slugger) {}

    public function load(ObjectManager $manager): void
    {
        $skills = [
            'Cardiologue',
            'Dermatologue',
            'Gynécologue',
            'Ophtalmologue',
            'Oto-rhino-laryngologiste',
            'Pédiatre',
            'Psychiatre',
            'Radiologue',
            'Urologue',
            'Médecin Généraliste',
            'Chirurgien',
            'Infirmier',
            'Sage-femme',
            'Kinésithérapeute',
            'Orthophoniste',
            'Psychologue',
            'Diététicien',
            'Podologue',
            'Orthoptiste',
            'Ergothérapeute',
            'Psychomotricien',
            'Ostéopathe',
            'Chiropracteur',
            'Acupuncteur',
            'Homéopathe',
            'Dentiste',
        ];

        foreach ($skills as $skill) {
            $skillEntity = new Skill();
            $skillEntity->setName($skill);
            $manager->persist($skillEntity);
            $this->addReference('skill_' . strtolower($this->slugger->slug($skill)), $skillEntity);
        }

        $manager->flush();
    }
}