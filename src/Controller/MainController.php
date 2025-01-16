<?php

namespace App\Controller;

use App\Repository\HealthcareCenterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(HealthcareCenterRepository $repository): Response
    {
        return $this->render('main/index.html.twig', [
            'healthcare_centers' => $repository->findAll(),
        ]);
    }

    #[Route('/contact', name: 'app_main_contact')]
    public function contact(): Response
    {
        return $this->render('main/contact.html.twig');
    }
}