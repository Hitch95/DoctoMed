<?php

namespace App\Controller\Admin;

use App\Entity\Doctor;
use App\Form\DoctorType;
use App\Repository\DoctorRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/doctor')]
final class DoctorController extends AbstractController
{
    #[Route(name: 'app_admin_doctor_index', methods: ['GET'])]
    public function index(DoctorRepository $doctorRepository): Response
    {
        return $this->render('admin/doctor/index.html.twig', [
            'doctors' => $doctorRepository->findAll(),
        ]);
    }

    // Add a new Doctor
    #[Route('/new', name: 'app_admin_doctor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $doctor = new Doctor();
        $form = $this->createForm(DoctorType::class, $doctor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();
            if ($photo) {
                $photoFileName = $fileUploader->upload($photo);
                $doctor->setPhoto($photoFileName);
            }
            $entityManager->persist($doctor);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_doctor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/doctor/new.html.twig', [
            'doctor' => $doctor,
            'form' => $form,
        ]);
    }

    // Show Doctor
    #[Route('/{id}', name: 'app_admin_doctor_show', methods: ['GET'])]
    public function show(Doctor $doctor): Response
    {
        return $this->render('admin/doctor/show.html.twig', [
            'doctor' => $doctor,
        ]);
    }

    // Edit Doctor
    #[Route('/{id}/edit', name: 'app_admin_doctor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Doctor $doctor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DoctorType::class, $doctor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the HealthcareCenter from the form data
            $healthcareCenter = $form->get('healthcareCenter')->getData();
            // Set the HealthcareCenter for the doctor
            $doctor->setHealthcareCenter($healthcareCenter);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_doctor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/doctor/edit.html.twig', [
            'doctor' => $doctor,
            'form' => $form,
        ]);
    }

    // Delete Doctor
    #[Route('/{id}', name: 'app_admin_doctor_delete', methods: ['POST'])]
    public function delete(Request $request, Doctor $doctor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$doctor->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($doctor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_doctor_index', [], Response::HTTP_SEE_OTHER);
    }
}