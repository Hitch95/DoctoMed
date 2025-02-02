<?php
namespace App\Security;

use App\Entity\HealthcareCenter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class HealthcareCenterVoter extends Voter
{
    public const MANAGE = 'HEALTHCARE_CENTER_MANAGE';

    private RoleChecker $roleChecker;

    public function __construct(RoleChecker $roleChecker)
    {
        $this->roleChecker = $roleChecker;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // Only vote on HealthcareCenter objects with the "MANAGE" attribute
        return $attribute === self::MANAGE && $subject instanceof HealthcareCenter;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var HealthcareCenter $subject */
        try {
            return $this->roleChecker->manageHealthcareCenter($subject);
        } catch (AccessDeniedException $e) {
            return false;
        }
    }
}
