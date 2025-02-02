<?php

namespace App\DataFixtures;

use App\Entity\HealthcareCenter;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {}

    public function load(ObjectManager $manager): void
    {
        // Admin
        $admin = new User();
        $admin->setEmail('admin@salutem.com');
        $admin->setRoles([User::ROLE_ADMIN]);
        $admin->setPassword($this->hasher->hashPassword($admin, '1234'));
        $admin->setIsVerified(true);
        $manager->persist($admin);

        // Manager
        $managerUser = new User();
        $managerUser->setEmail('manager@manager.com');
        $managerUser->setRoles([User::ROLE_MANAGER]);
        $managerUser->setPassword($this->hasher->hashPassword($managerUser, '1234'));
        $managerUser->setIsVerified(true);

        // Get the "Cabinet des Acacias" healthcare center reference
        $healthcareCenterAcacias = $this->getReference('healthcare_center_acacias', HealthcareCenter::class);
        $managerUser->setHealthcareCenter($healthcareCenterAcacias);
        $manager->persist($managerUser);

        // User
        $user = new User();
        $user->setEmail('contact@acacias.com');
        $user->setRoles([User::ROLE_USER]);
        $user->setPassword($this->hasher->hashPassword($user, '1234'));
        $user->setIsVerified(true);
        $manager->persist($user);

        $manager->flush();
    }
}