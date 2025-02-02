<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\HealthcareCenter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

readonly class RoleChecker
{
    public function __construct(
        private Security $security,
    ) {}

    public function manageHealthcareCenter(User $user,HealthcareCenter $healthcareCenter): bool
    {
        /** @var User|null $user */
        $user = $this->security->getUser();
        dump($user->getRoles());
        dump($user->getHealthcareCenter());

        // 1. Check if user is authenticated
        if (!$user instanceof User) {
            throw new AccessDeniedException('User not authenticated');
        }

        // 2. Admin(s) can manage any healthcare centers
        if ($this->security->isGranted(User::ROLE_ADMIN)) {
            return true;
        }

        // 3. Manager check using entity relationship
        if ($this->security->isGranted(User::ROLE_MANAGER)) {
            return $healthcareCenter->getManagers()->contains($user);
        }
        return false;
    }
}