<?php

namespace App\Controller;

use App\Entity\DirecteurCommercial;
use App\Repository\DirecteurCommercialRepository;
use App\Repository\VehiculeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

class DirecteurComercialController extends AbstractController
{
    private $vehiculeRepository;
    private $directeurCommercialRepository;
    private $logger;

    public function __construct(DirecteurCommercialRepository $directeurCommercialRepository, LoggerInterface $logger, VehiculeRepository $vehiculeRepository)
    {
        $this->vehiculeRepository = $vehiculeRepository;
        $this->directeurCommercialRepository = $directeurCommercialRepository;
        $this->logger = $logger;
    }

    #[Route('/directeur-commercial/vehicules', name: 'directeur_commercial_vehicules', methods: ['GET'])]
    public function viewLimitedVehicules(): Response
    {
        // Get the current Directeur Commercial
        $directeurCommercial = $this->getUser();
        if (!$directeurCommercial instanceof DirecteurCommercial) {
            throw $this->createAccessDeniedException('Access denied.');
        }

        // Fetch department and limit
        $department = $directeurCommercial->getDepartement();
        $limit = $directeurCommercial->getNombreLimiteVehicules(); // Assuming this method returns the limit

        // Fetch a limited number of vehicles from the specific department
        $vehicules = $this->vehiculeRepository->findByDepartmentWithLimit($department, $limit);

        return $this->render('directeur_commercial/vehicules.html.twig', [
            'vehicules' => $vehicules,
        ]);
    }

    #[Route('/directeur-commercial/rapport', name: 'directeur_commercial_rapport', methods: ['GET'])]
    public function rapport(): Response
    {
        // Assuming you have some logic to get the current DirecteurCommercial
        $directeurCommercial = $this->getUser(); // Adjust as needed

        if (!$directeurCommercial instanceof DirecteurCommercial) {
            throw $this->createAccessDeniedException('Access denied.');
        }

        // Generate and fetch reports using the class property
        $rapports = $this->directeurCommercialRepository->genererRapportsAnalyses($directeurCommercial);

        // Optionally, log the report generation
        $this->logger->info('Generated reports for DirecteurCommercial', [
            'directeur_commercial' => $directeurCommercial->getId(),
            'rapports' => $rapports
        ]);

        // Render the view with report data
        return $this->render('directeur_commercial/rapport.html.twig', [
            'rapports' => $rapports,
        ]);
    }

    #[Route('/directeur-commercial/evaluation', name: 'directeur_commercial_evaluation', methods: ['GET'])]
    public function efficaciteGestion(): Response
    {
        $directeurCommercial = $this->getUser();

        if (!$directeurCommercial instanceof DirecteurCommercial) {
            throw $this->createAccessDeniedException('Access denied.');
        }

        // Control efficiency using the class property
        $this->directeurCommercialRepository->controlerEfficaciteGestion($directeurCommercial);

        // Optionally, log the efficiency control
        $this->logger->info('Efficiency control executed for DirecteurCommercial', [
            'directeur_commercial' => $directeurCommercial->getId(),
        ]);

        // Render the view
        return $this->render('directeur_commercial/evaluation.html.twig');
    }
}
