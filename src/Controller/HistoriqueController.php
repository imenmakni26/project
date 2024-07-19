<?php

namespace App\Controller;

use App\Entity\Historique;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HistoriqueController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/historique', name: 'app_historique')]
    public function index(): Response
    {
        return $this->render('historique/index.html.twig', [
            'controller_name' => 'historiqueController',
        ]);
    }
    /**
     * @Route("/{id}", name="historique_show", methods={"GET"})
     */
    public function show(Historique $historique): Response
    {
        return $this->json($historique);
    }
    /**
     * @Route("/", name="historique_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $historique = new historique();
        $historique->setDate($data['date'] ?? null);
        $historique->setDescription($data['description'] ?? null);
        $historique->setCout($data['cout'] ?? null);
        $historique->setVehicule($data['vehicule'] ?? null);
        $historique->setArchive($data['archive'] ?? null);

        $entityManager = $this->entityManager;
        $entityManager->persist($historique);
        $entityManager->flush();

        return $this->json($historique);
    }

    /**
     * @Route("/{id}", name="historique_update", methods={"PUT"})
     */
    public function update(Request $request, Historique $historique): Response
    {
        $data = json_decode($request->getContent(), true);

        $historique->setDate($data['date'] ?? $historique->getDate());
        $historique->setDescription($data['description'] ?? $historique->getDescription());
        $historique->setCout($data['cout'] ?? $historique->getCout());
        $historique->setVehicule($data['vehicule'] ?? $historique->getVehicule());
        $historique->setArchive($data['archive'] ?? $historique->isArchive());

        $entityManager = $this->entityManager;
        $entityManager->flush();

        return $this->json($historique);
    }
    public function archive(Historique $historique): Response
    {
        $entityManager = $this->entityManager;
        $historique->setArchive(true);
    
        $entityManager->persist($historique);
        $entityManager->flush();
    
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
