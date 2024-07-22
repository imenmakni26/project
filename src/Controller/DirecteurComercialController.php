<?php

namespace App\Controller;

use App\Entity\DirecteurCommercial;
use App\Repository\DirecteurCommercialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

class DirecteurComercialController extends AbstractController
{
    private $directeurCommercialRepository;
    private $logger;

    public function __construct(DirecteurCommercialRepository $directeurCommercialRepository, LoggerInterface $logger)
    {
        $this->directeurCommercialRepository = $directeurCommercialRepository;
        $this->logger = $logger;
    }

    #[Route('/directeur-commercial/rapport', name: 'directeur_commercial_rapport')]
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

    #[Route('/directeur-commercial/evaluation', name: 'directeur_commercial_evaluation')]
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