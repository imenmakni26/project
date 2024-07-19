<?php

namespace App\Controller;

use App\Entity\Departement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


class DepartementController extends AbstractController
{
    #[Route('/departement', name: 'app_departement')]
    public function index(): Response
    {
        return $this->render('departement/index.html.twig', [
            'controller_name' => 'DepartementController',
        ]);
    }
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/departement/{id}", name="carburant_show", methods={"GET"})
     */
    public function show(Departement $departement): Response
    {
        return $this->json($departement);
    }

    /**
     * @Route("/departement", name="departement_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $departement = new Departement();
        $departement->setNom($data['nom'] ?? null);
        $departement->setArchive($data['archive'] ?? null);       

        $entityManager = $this->entityManager;
        $entityManager->persist($departement);
        $entityManager->flush();

        return $this->json($departement);
    }

    /**
     * @Route("/departement/{id}", name="departement_update", methods={"PUT"})
     */
    public function update(Request $request, Departement $departement): Response
    {
        $data = json_decode($request->getContent(), true);

        $departement->setNom($data['nom'] ?? $departement->getNom());
        $departement->setArchive($data['archive'] ?? $departement->isArchive());

        $entityManager = $this->entityManager;
        $entityManager->flush();

        return $this->json($departement);
    }

    public function archive(Departement $departement): Response
    {
        $entityManager = $this->entityManager;
        $departement->setArchive(true);

        $entityManager->persist($departement);
        $entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

}
