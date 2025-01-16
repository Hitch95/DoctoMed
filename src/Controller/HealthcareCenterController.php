<?php

namespace App\Controller;

use App\Entity\HealthcareCenter;
use App\Repository\HealthcareCenterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/healthcarecenter', name: 'app_healthcarecenter_')]

final class HealthcareCenterController extends AbstractController
{
    #[Route('/{slug}', name: 'show')]
    public function show(string $slug, HealthcareCenterRepository $repository): Response
    {
        $healthcareCenter = $repository->findOneWithDoctors($slug);
        return $this->render('healthcare_center/show.html.twig', [
            'healthcare_center' => $healthcareCenter,
        ]);
    }
}
