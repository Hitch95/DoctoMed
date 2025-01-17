<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentType;
use App\Repository\HealthcareCenterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/healthcarecenter', name: 'app_healthcarecenter_')]
final class HealthcareCenterController extends AbstractController
{
    #[Route('/{slug}', name: 'show')]
    public function show(
        string $slug,
        HealthcareCenterRepository $repository,
        RequestStack $requestStack,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $healthcareCenter = $repository->findOneWithDoctors($slug);

        if ($healthcareCenter === null) {
            throw $this->createNotFoundException();
        }

        $requestStack->getSession()->set('healthcare_center_id', $healthcareCenter->getId());

        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment, [
            'healthcare_center' => $healthcareCenter,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $appointment->setHealthcareCenter($healthcareCenter);
            $entityManager->persist($appointment);
            $entityManager->flush();

            return $this->redirectToRoute('app_healthcarecenter_show', ['slug' => $healthcareCenter->getSlug()]);
        }

        return $this->render('healthcare_center/show.html.twig', [
            'healthcare_center' => $healthcareCenter,
            'form' => $form,
        ]);
    }
}