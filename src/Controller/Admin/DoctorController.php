<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Doctor;
use App\Form\DoctorType;
use App\Repository\DoctorRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/admin/doctor')]
final class DoctorController extends AbstractController
{
    private function getCurrentUser(): User
    {
        /** @var User $user */
        $user = $this->getUser();
        return $user;
    }

    private function checkManagerAccess(Doctor $doctor): void
    {
        $user = $this->getCurrentUser();
        $healthcareCenter = $doctor->getHealthcareCenter();

        if (!$healthcareCenter || (!$user->isAdmin() && !$user->isManagerOf($healthcareCenter))) {
            throw new AccessDeniedException('Access Denied.');
        }
    }

    private function prepareDoctorForm(Doctor $doctor, bool $isNewDoctor = false): FormInterface
    {
        $user = $this->getCurrentUser();

        // Set default healthcare center for managers on new doctors or when editing
        if ($user->isManager()) {
            $doctor->setHealthcareCenter($user->getHealthcareCenter());
        }

        return $this->createForm(DoctorType::class, $doctor, [
            'is_admin' => $user->isAdmin()
        ]);
    }

    private function handleDoctorPersistence(Doctor $doctor, ?UploadedFile $photo = null, FileUploader $fileUploader = null): void
    {
        $user = $this->getCurrentUser();

        // Verify manager can only assign to their center
        if ($user->isManager() && $doctor->getHealthcareCenter() !== $user->getHealthcareCenter()) {
            throw new AccessDeniedException('You can only manage doctors for your own healthcare center');
        }

        // Handle photo upload if applicable
        if ($photo && $fileUploader) {
            $doctor->setPhoto($fileUploader->upload($photo));
        }
    }

    // GET Doctors
    #[Route(name: 'app_admin_doctor_index', methods: ['GET'])]
    public function index(DoctorRepository $doctorRepository): Response
    {
        $user = $this->getCurrentUser();

        $doctors = match(true) {
            $user->isAdmin() => $doctorRepository->findAll(),
            $user->isManager() => $doctorRepository->findBy(['healthcareCenter' => $user->getHealthcareCenter()]),
            default => throw new AccessDeniedException('Access Denied.')
        };

        return $this->render('admin/doctor/index.html.twig', [
            'doctors' => $doctors,
        ]);
    }

    // CREATE a new Doctor
    #[Route('/new', name: 'app_admin_doctor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $doctor = new Doctor();
        $form = $this->prepareDoctorForm($doctor, true);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleDoctorPersistence(
                $doctor,
                $form->get('photo')->getData(),
                $fileUploader
            );

            $entityManager->persist($doctor);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_doctor_index');
        }

        return $this->render('admin/doctor/new.html.twig', [
            'doctor' => $doctor,
            'form' => $form->createView(),
        ]);
    }

    // SHOW one Doctor
    #[Route('/{id}', name: 'app_admin_doctor_show', methods: ['GET'])]
    public function show(Doctor $doctor): Response
    {
        $this->checkManagerAccess($doctor);

        $appointments = $doctor->getAppointments();

        return $this->render('admin/doctor/show.html.twig', [
            'doctor' => $doctor,
            'appointments' => $appointments,
        ]);
    }

    // EDIT a Doctor
    #[Route('/{id}/edit', name: 'app_admin_doctor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Doctor $doctor, EntityManagerInterface $entityManager): Response
    {
        $this->checkManagerAccess($doctor);

        $originalHealthcareCenter = $doctor->getHealthcareCenter();
        $form = $this->prepareDoctorForm($doctor);

        $appointments = $doctor->setAppointments($doctor->getAppointments());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getCurrentUser();

            // Prevent managers from changing healthcare center
            if ($user->isManager() && $doctor->getHealthcareCenter() !== $originalHealthcareCenter) {
                $doctor->setHealthcareCenter($originalHealthcareCenter);
                $this->addFlash('warning', 'You cannot change the healthcare center affiliation');
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_admin_doctor_index');
        }

        return $this->render('admin/doctor/edit.html.twig', [
            'doctor' => $doctor,
            'form' => $form->createView(),
            'appointments' => $appointments,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_doctor_delete', methods: ['POST'])]
    public function delete(Request $request, Doctor $doctor, EntityManagerInterface $entityManager): Response
    {
        $this->checkManagerAccess($doctor);

        if ($this->isCsrfTokenValid('delete'.$doctor->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($doctor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_doctor_index');
    }
}