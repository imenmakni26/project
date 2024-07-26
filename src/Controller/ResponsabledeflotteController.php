<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Entity\Responsabledeflotte;
use App\Repository\ResponsabledeflotteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ResponsabledeflotteController extends AbstractController
{
    private $responsabledeflotterepository;

    public function __construct(ResponsabledeflotteRepository $responsabledeflotterepository)
    {
        $this->responsabledeflotterepository = $responsabledeflotterepository;
    }

    #[Route('/vehicule/historique', name: 'vehicule_historique')]
    public function suivreHistoriqueVehicule(Vehicule $vehicle): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Responsabledeflotte) {
            throw $this->createAccessDeniedException('Access denied.');
        }

        $history = $this->responsabledeflotterepository->suivreHistoriqueVehicule($vehicle);

        return $this->render('vehicule/historique.html.twig', [
            'vehicle' => $vehicle,
            'history' => $history,
            'controller_name' => 'ResponsabledeflotteController',
        ]);
    }

    #[Route('/vehicule/budget', name: 'vehicule_budget')]
    public function gererBudgetVehicule(Request $request, Vehicule $vehicle): Response
    {
        $user = $this->getUser();
        if (!$user instanceof Responsabledeflotte) {
            throw $this->createAccessDeniedException('Access denied.');
        }

        $expense = (float)$request->request->get('expense');
        $this->responsabledeflotterepository->gererBudgetVehicule($vehicle, $expense);

        return $this->redirectToRoute('vehicule_show', ['id' => $vehicle->getId()]);
    }
}
