<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarburantController extends AbstractController
{
    #[Route('/carburant', name: 'app_carburant')]
    public function index(): Response
    {
        return $this->render('carburant/index.html.twig', [
            'controller_name' => 'CarburantController',
        ]);
    }
}
