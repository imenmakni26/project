<?php

namespace App\Controller;

use App\Entity\Assurance;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AssuranceController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/assurance/{id}", name="assurance_show", methods={"GET"})
     */
    public function show(Assurance $assurance): Response
    {
        return $this->json($assurance);
    }

    /**
     * @Route("/assurance", name="assurance_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $assurance = new Assurance();
        $assurance->setNumero($data['Numero'] ?? null);
        $assurance->setType($data['Type'] ?? null);
        $assurance->setAgence($data['Agence'] ?? null);
        $assurance->setDate($data['Date'] ?? null);

        $entityManager = $this->entityManager;
        $entityManager->persist($assurance);
        $entityManager->flush();

        return $this->json($assurance);
    }

    /**
     * @Route("/assurance/{id}", name="assurance_update", methods={"PUT"})
     */
    public function update(Request $request, Assurance $assurance): Response
    {
        $data = json_decode($request->getContent(), true);

        $assurance->setNumero($data['numero'] ?? $assurance->getNumero());
        $assurance->setType($data['type'] ?? $assurance->getType());
        $assurance->setAgence($data['agence'] ?? $assurance->getAgence());
        $assurance->setDate($data['date'] ?? $assurance->getDate());

        $entityManager = $this->entityManager;
        $entityManager->flush();

        return $this->json($assurance);
    }

    /**
     * @Route("/assurance/{id}", name="assurance_delete", methods={"DELETE"})
     */
    public function delete(Assurance $assurance): Response
    {
        $entityManager = $this->entityManager;
        $entityManager->remove($assurance);
        $entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
