<?php

namespace App\Controller;

use App\Entity\Vehicule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VehiculeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/vehicules/{id}", name="vehicule_show", methods={"GET"})
     */
    public function show(Vehicule $vehicule): Response
    {
        return $this->json($vehicule);
    }

    /**
     * @Route("/vehicules", name="vehicule_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $vehicule = new Vehicule();
        $vehicule->setId($data['id'] ?? null);
        $vehicule->setMarque($data['marque'] ?? null);
        $vehicule->setModele($data['modele'] ?? null);
        $vehicule->setAnnee($data['annee'] ?? null);
        $vehicule->setImmatriculation($data['immatriculation'] ?? null);
        $vehicule->setNumSerieCarburant($data['NumSerieCarburant'] ?? null);
        $vehicule->setEtat($data['etat'] ?? null);
        $vehicule->setDescription ($data['description '] ?? null);
        $vehicule->setCouleur($data['couleur'] ?? null);
        $vehicule->setPrix($data['prix'] ?? null);
        $vehicule->setNumeroSerie($data['NumeroSerie'] ?? null);
        $vehicule->setKilometrage($data['kilometrage'] ?? null);
        $vehicule->setType($data['type'] ?? null);
        $vehicule->setDimensionRoue($data['dimensionRoue'] ?? null);
        $vehicule->setEntretient($data['entretient'] ?? null);
        $vehicule->setDateDerniereVidange($data['dateDerniereVidange'] ?? null);
        $vehicule->setCartePeage($data['cartePeage'] ?? null);
        $vehicule->setNumeroAssurance($data['NumeroAssurance'] ?? null);
        $vehicule->setIdBudget($data['idBudget'] ?? null);

        $entityManager = $this->entityManager;
        $entityManager->persist($vehicule);
        $entityManager->flush();

        return $this->json($vehicule);
    }

    /**
     * @Route("/vehicules/{id}", name="vehicule_update", methods={"PUT"})
     */
    public function update(Request $request, Vehicule $vehicule): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $vehicule->setId($data['id'] ?? $vehicule->getId());
        $vehicule->setMarque($data['marque'] ?? $vehicule->getMarque());
        $vehicule->setModele($data['modele'] ?? $vehicule->getModele());
        $vehicule->setAnnee($data['annee'] ?? $vehicule->getAnnee());
        $vehicule->setImmatriculation($data['immatriculation'] ?? $vehicule->getImmatriculation());
        $vehicule->setNumSerieCarburant($data['NumSerieCarburant'] ?? $vehicule->getNumSerieCarburant());
        $vehicule->setEtat($data['etat'] ?? $vehicule->getEtat());
        $vehicule->setDescription ($data['description '] ?? $vehicule->getDescription());
        $vehicule->setCouleur($data['couleur'] ?? $vehicule->getCouleur());
        $vehicule->setPrix($data['prix'] ?? $vehicule->getPrix());
        $vehicule->setNumeroSerie($data['NumeroSerie'] ?? $vehicule->getNumeroSerie());
        $vehicule->setKilometrage($data['kilometrage'] ?? $vehicule->getKilometrage());
        $vehicule->setType($data['type'] ?? $vehicule->getType());
        $vehicule->setDimensionRoue($data['dimensionRoue'] ?? $vehicule->getDimensionRoue());
        $vehicule->setEntretient($data['entretient'] ?? $vehicule->getEntretient());
        $vehicule->setDateDerniereVidange($data['dateDerniereVidange'] ?? $vehicule->getDateDerniereVidange());
        $vehicule->setCartePeage($data['cartePeage'] ?? $vehicule->getCartePeage());
        $vehicule->setNumeroAssurance($data['NumeroAssurance'] ?? $vehicule->getNumeroAssurance());
        $vehicule->setIdBudget($data['idBudget'] ?? $vehicule->getIdBudget());

        $entityManager = $this->entityManager;
        $entityManager->flush();

        return $this->json($vehicule);
    }

    /**
     * @Route("/vehicules/{id}", name="vehicule_delete", methods={"DELETE"})
     */
    public function delete(Vehicule $vehicule): Response
    {
        $entityManager = $this->entityManager;
        $entityManager->remove($vehicule);
        $entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
