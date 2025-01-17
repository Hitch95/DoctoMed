<?php

namespace App\Controller\Admin;

use App\Entity\HealthcareCenter;
use App\Form\HealthcareCenterType;
use App\Repository\HealthcareCenterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/healthcare/center')]
final class HealthcareCenterController extends AbstractController
{
    #[Route(name: 'app_admin_healthcare_center_index', methods: ['GET'])]
    public function index(HealthcareCenterRepository $healthcareCenterRepository): Response
    {
        return $this->render('admin/healthcare_center/index.html.twig', [
            'healthcare_centers' => $healthcareCenterRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_healthcare_center_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $healthcareCenter = new HealthcareCenter();
        $form = $this->createForm(HealthcareCenterType::class, $healthcareCenter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($healthcareCenter);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_healthcare_center_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/healthcare_center/new.html.twig', [
            'healthcare_center' => $healthcareCenter,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_healthcare_center_show', methods: ['GET'])]
    public function show(HealthcareCenter $healthcareCenter): Response
    {
        return $this->render('admin/healthcare_center/show.html.twig', [
            'healthcare_center' => $healthcareCenter,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_healthcare_center_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, HealthcareCenter $healthcareCenter, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HealthcareCenterType::class, $healthcareCenter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_healthcare_center_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/healthcare_center/edit.html.twig', [
            'healthcare_center' => $healthcareCenter,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_healthcare_center_delete', methods: ['POST'])]
    public function delete(Request $request, HealthcareCenter $healthcareCenter, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$healthcareCenter->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($healthcareCenter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_healthcare_center_index', [], Response::HTTP_SEE_OTHER);
    }
}