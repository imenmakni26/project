<?php

namespace App\Controller;

use App\Entity\Directeur;
use App\Repository\DirecteurRepository;
use App\Repository\VehiculeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DirecteurController extends AbstractController
{
    private $directeurRepository;
    private $vehiculeRepository;

    public function __construct(DirecteurRepository $directeurRepository, VehiculeRepository $vehiculeRepository)
    {
        $this->directeurRepository = $directeurRepository;
        $this->vehiculeRepository = $vehiculeRepository;
    }


    #[Route('/directeur/vehicules', name: 'directeur_vehicules', methods: ['GET'])]
    public function viewAllVehicules(): Response
    {
        $vehicules = $this->vehiculeRepository->findAll();

        return $this->render('directeur/vehicules.html.twig', [
            'vehicules' => $vehicules,
        ]);
    }

    #[Route('/directeur/rapports', name: 'directeur_rapports', methods: ['GET'])]
    public function rapports(): Response
    {
        // Ensure the user is authenticated and has the correct role
        $user = $this->getUser();
        if (!$user instanceof Directeur) {
            throw $this->createAccessDeniedException('Access denied.');
        }

        $rapports = $this->directeurRepository->genererRapportsAnalyses();

        return $this->render('directeur/rapports.html.twig', [
            'rapports' => $rapports,
        ]);
    }

    #[Route('/directeur/efficacite', name: 'directeur_efficacite', methods: ['GET'])]
    public function efficaciteGestion(DirecteurRepository $directeurRepository): Response
    {
        // Ensure the user is authenticated and has the correct role
        $user = $this->getUser();
        if (!$user instanceof Directeur) {
            throw $this->createAccessDeniedException('Access denied.');
        }

        // Execute the controlerEfficaciteGestion method
        $directeurRepository->controlerEfficaciteGestion();

        // Render the view
        return $this->render('directeur/efficacite.html.twig');
    }
}
