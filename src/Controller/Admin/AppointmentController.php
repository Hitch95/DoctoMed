<?php

namespace App\Controller\Admin;

use App\Entity\Appointment;
use App\Entity\User;
use App\Form\AppointmentType;
use App\Repository\AppointmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/admin/appointment')]
class AppointmentController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    // GET Appointment(s)
    #[Route(name: 'app_admin_appointment_index', methods: ['GET'])]
    public function index(AppointmentRepository $repository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $healthcareCenter = $user->getHealthcareCenter();

        if (!$user->isManagerOf($healthcareCenter)) {
            throw new AccessDeniedException('Access Denied.');
        }

        return $this->render('admin/appointment/index.html.twig', [
            'appointments' => $repository->findByHealthcareCenter($healthcareCenter)
        ]);
    }

    // CREATE Appointment(s)
    #[Route('/new', name: 'app_admin_appointment_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($appointment);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_admin_appointment_index');
        }

        return $this->render('admin/appointment/new.html.twig', [
            'appointment' => $appointment,
            'form' => $form->createView(),
        ]);
    }

    // SHOW Appointment
    #[Route('/{id}', name: 'app_admin_appointment_show', methods: ['GET'])]
    public function show(Appointment $appointment): Response
    {
        $this->checkAccess($appointment);
        return $this->render('admin/appointment/show.html.twig', [
            'appointment' => $appointment
        ]);
    }

    // EDIT Appointment(s)
    #[Route('/{id}/edit', name: 'app_admin_appointment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Appointment $appointment): Response
    {
        $this->checkAccess($appointment);

        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_admin_appointment_index');
        }

        return $this->render('admin/appointment/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form->createView(),
        ]);
    }

    // DELETE Appointment(s)
    #[Route('/{id}', name: 'app_admin_appointment_delete', methods: ['POST'])]
    public function delete(Request $request, Appointment $appointment): Response
    {
        $this->checkAccess($appointment);

        if ($this->isCsrfTokenValid('delete'.$appointment->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($appointment);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_appointment_index');
    }

    private function checkAccess(Appointment $appointment): void
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user->isManagerOf($appointment->getHealthcareCenter())) {
            throw new AccessDeniedException('Access Denied.');
        }
    }
}