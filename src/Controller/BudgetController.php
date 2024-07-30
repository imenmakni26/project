<?php

namespace App\Controller;

use App\Entity\Budget;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BudgetController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/budget', name: 'budget_index')]
    public function index(): Response
    {
        return $this->render('budget/index.html.twig', [
            'controller_name' => 'BudgetController',
        ]);
    }
    /**
     * @Route("/budget/show/{id}", name="budget_show")
     */
    public function show(Budget $budget): Response
    {
        if (!$budget) {
            throw $this->createNotFoundException('No budget found for id '.$budget->getId());
        }

        return $this->render('budget/show.html.twig', [
            'budget' => $budget,
        ]);
    }

    /**
     * @Route("/budget/create", name="budget_create")
     */
    public function create(Request $request): Response
{
    // Décoder le contenu JSON de la requête
    $data = json_decode($request->getContent(), true);
   
    $budget = new Budget();

    // Vérifier et définir le montant alloué
    $montantAlloue = isset($data['montant_alloue']) ? (float) $data['montant_alloue'] : 0.0;
    $budget->setMontantAlloue($montantAlloue);

    // Vérifier et définir la dépense
    $depense = isset($data['depense']) ? (float) $data['depense'] : 0.0;
    $budget->setDepense($depense);

    // Vérifier et définir l'archive
    $archive = isset($data['archive']) ? (bool) $data['archive'] : false;
    $budget->setArchive($archive);

    // Persister et flusher l'entité
    $this->entityManager->persist($budget);
    $this->entityManager->flush();

    // Retourner la réponse
    return $this->render('budget/create.html.twig', [
        'budget' => $budget,
    ]);
}

    

    /**
     * @Route("/budget/update/{id}", name="budget_update", methods={"PUT"})
     */
    public function update(Request $request, Budget $budget): Response
    {
        $data = json_decode($request->getContent(), true);

        $budget->setId($data['Id'] ?? $budget->getId());
        $budget->setMontantAlloue($data['MontantAlloue'] ?? $budget->getMontantAlloue());
        $budget->setdepense($data['depense'] ?? $budget->getDepense());
        $budget->setArchive($data['archive'] ?? $budget->isArchive());

        $entityManager = $this->entityManager;
        $entityManager->flush();

        return $this->render('budget/update.html.twig', [
            'controller_name' => 'BudgetController',
        ]);
    }

    /**
     * @Route(/budget/archive/{id}", name="budget_archive")
     */
    public function archive(Budget $budget): Response
    {
        $entityManager = $this->entityManager;
        $budget->setArchive(true);

        $entityManager->persist($budget);
        $entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
     
}
