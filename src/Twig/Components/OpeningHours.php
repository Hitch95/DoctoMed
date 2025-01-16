<?php

namespace App\Twig\Components;

use App\Repository\HealthcareCenterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class OpeningHours
{

    public function __construct(
        private RequestStack $requestStack,
        private HealthcareCenterRepository $repository
    ) {}

    public function getOpeningHours(): Collection
    {
        $id = $this->requestStack->getSession()->get('healthcare_center_id');
        $healthcareCenter = $this->repository->find($id);

        if ($healthcareCenter === null) {
            return new ArrayCollection();
        }

        return $healthcareCenter->getOpeningHours();
    }
}