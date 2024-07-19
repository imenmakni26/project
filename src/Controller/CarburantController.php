<?php

namespace App\Controller;

use App\Entity\Carburant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarburantController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/carburant/{id}", name="carburant_show", methods={"GET"})
     */
    public function show(Carburant $carburant): Response
    {
        return $this->json($carburant);
    }

    /**
     * @Route("/carburant", name="carburant_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $carburant = new Carburant();
        $carburant->setnumserie($data['numserie'] ?? null);
        $carburant->setvaleur($data['valeur'] ?? null);
        $carburant->setmotDePasse($data['motDePasse'] ?? null);
        $carburant->setArchive($data['archive'] ?? null);       

        $entityManager = $this->entityManager;
        $entityManager->persist($carburant);
        $entityManager->flush();

        return $this->json($carburant);
    }

    /**
     * @Route("/carburant/{id}", name="carburant_update", methods={"PUT"})
     */
    public function update(Request $request, Carburant $carburant): Response
    {
        $data = json_decode($request->getContent(), true);

        $carburant->setnumserie($data['numserie'] ?? $carburant->getnumserie());
        $carburant->setvaleur($data['valeur'] ?? $carburant->getvaleur());
        $carburant->setmotDePasse($data['motDePasse'] ?? $carburant->getmotDePasse());
        $carburant->setArchive($data['archive'] ?? $carburant->isArchive());

        $entityManager = $this->entityManager;
        $entityManager->flush();

        return $this->json($carburant);
    }

    public function archive(Carburant $carburant): Response
    {
        $entityManager = $this->entityManager;
        $carburant->setArchive(true);

        $entityManager->persist($carburant);
        $entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
