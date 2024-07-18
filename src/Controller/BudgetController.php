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

    #[Route('/budget', name: 'app_budget')]
    public function index(): Response
    {
        return $this->render('budget/index.html.twig', [
            'controller_name' => 'BudgetController',
        ]);
    }
    /**
     * @Route("/{id}", name="budget_show", methods={"GET"})
     */
    public function show(Budget $budget): Response
    {
        return $this->json($budget);
    }
    /**
     * @Route("/", name="budget_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $budget = new budget();
        $budget->setId($data['Id'] ?? null);
        $budget->setMontantAlloue($data['MontantAlloue'] ?? null);
        $budget->setdepense($data['depense'] ?? null);

        $entityManager = $this->entityManager;
        $entityManager->persist($budget);
        $entityManager->flush();

        return $this->json($budget);
    }

    /**
     * @Route("/{id}", name="budget_update", methods={"PUT"})
     */
    public function update(Request $request, Budget $budget): Response
    {
        $data = json_decode($request->getContent(), true);

        $budget->setId($data['Id'] ?? $budget->getId());
        $budget->setMontantAlloue($data['MontantAlloue'] ?? $budget->getMontantAlloue());
        $budget->setdepense($data['depense'] ?? $budget->getdepense());

        $entityManager = $this->entityManager;
        $entityManager->flush();

        return $this->json($budget);
    }
}
