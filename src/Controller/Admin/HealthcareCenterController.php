<?php

namespace App\Controller\Admin;

use App\Entity\HealthcareCenter;
use App\Entity\User;
use App\Form\HealthcareCenterType;
use App\Repository\HealthcareCenterRepository;
use App\Security\RoleChecker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/healthcare/center')]
final class HealthcareCenterController extends AbstractController
{
    public function __construct(private readonly RoleChecker $roleChecker) {}

    // GET Healthcare Center(s)
    #[Route(name: 'app_admin_healthcare_center_index', methods: ['GET'])]
    public function index(HealthcareCenterRepository $healthcareCenterRepository): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException('User not authenticated');
        }

        /** @var User $user */
        $user = $this->getUser();

        // Security check
        if ($user->isAdmin()) {
            $healthcareCenters = $healthcareCenterRepository->findAll();
        } elseif ($user->isManager()) {
            if ($user instanceof User) {
                $healthcareCenters = [$user->getHealthcareCenter()];
            } else {
                throw new AccessDeniedException('Access Denied.');
            }
        } else {
            throw new AccessDeniedException('Access Denied.');
        }

        return $this->render('admin/healthcare_center/index.html.twig', [
            'healthcare_centers' => $healthcareCenters,
        ]);
    }

    // ADD a new Healthcare Center
    #[Route('/new', name: 'app_admin_healthcare_center_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(User::ROLE_ADMIN);

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

    // SHOW a Healthcare Center(s)
    #[Route('/{id}', name: 'app_admin_healthcare_center_show', methods: ['GET'])]
    public function show(HealthcareCenter $healthcareCenter): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user->isManagerOf($healthcareCenter) && !$user->isAdmin()) {
            throw new AccessDeniedException('Access Denied.');
        }

        return $this->render('admin/healthcare_center/show.html.twig', [
            'healthcare_center' => $healthcareCenter,
        ]);
    }

    // EDIT Healthcare Center(s)
    #[Route('/{slug}/edit', name: 'app_admin_healthcare_center_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        string $slug,
        HealthcareCenterRepository $healthcareCenterRepo,
        EntityManagerInterface $entityManager
    ): Response {
        // Get healthcare center with doctors and their skills
        $healthcareCenter = $healthcareCenterRepo->findOneWithDoctors($slug);

        if (!$healthcareCenter) {
            throw $this->createNotFoundException('Healthcare center not found');
        }

        /** @var User $user */
        $user = $this->getUser();

        // Verify access
        if (!$user->isManagerOf($healthcareCenter) && !$user->isAdmin()) {
            throw new AccessDeniedException('Access Denied.');
        }

        $form = $this->createForm(HealthcareCenterType::class, $healthcareCenter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_healthcare_center_index');
        }

        return $this->render('admin/healthcare_center/edit.html.twig', [
            'healthcare_center' => $healthcareCenter,
            'form' => $form->createView(),
            'doctors' => $healthcareCenter->getDoctors() // Now available with skills
        ]);
    }

    // DELETE Healthcare Center(s)
    #[Route('/{id}', name: 'app_admin_healthcare_center_delete', methods: ['POST'])]
    public function delete(Request $request, HealthcareCenter $healthcareCenter, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user->isManagerOf($healthcareCenter) && !$user->isAdmin()) {
            throw new AccessDeniedException('Access Denied.');
        }

        if ($this->isCsrfTokenValid('delete'.$healthcareCenter->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($healthcareCenter);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_admin_healthcare_center_index', [], Response::HTTP_SEE_OTHER);
    }
}