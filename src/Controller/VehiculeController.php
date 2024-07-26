<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Entity\Assurance;
use App\Entity\Budget;
use App\Entity\Carburant;
use App\Entity\Entretient;
use App\Entity\Departement;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class VehiculeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/vehicules/show", name="vehicule_show", methods={"GET"})
     */
    public function show(Vehicule $vehicule): Response
    {
        return $this->render('vehicule/show.html.twig', [
            'controller_name' => 'vehiculeController',
        ]);
    }

    /**
     * @Route("/vehicules/create", name="vehicule_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $vehicule = new Vehicule();

        $vehicule->setMarque($data['marque'] ?? null);
        $vehicule->setModele($data['modele'] ?? null);
        $vehicule->setAnnee($data['annee'] ?? null);
        $vehicule->setImmatriculation($data['immatriculation'] ?? null);
        $vehicule->setEtat($data['etat'] ?? null);
        $vehicule->setDescription($data['description'] ?? null);
        $vehicule->setCouleur($data['couleur'] ?? null);
        $vehicule->setPrix($data['prix'] ?? null);
        $vehicule->setNumeroSerie($data['NumeroSerie'] ?? null);
        $vehicule->setKilometrage($data['kilometrage'] ?? null);
        $vehicule->setType($data['type'] ?? null);
        $vehicule->setDimensionRoue($data['dimensionRoue'] ?? null);
        $vehicule->setDateDerniereVidange($data['dateDerniereVidange'] ? new \DateTime($data['dateDerniereVidange']) : null);
        $vehicule->setCartePeage($data['cartePeage'] ?? null);
        $vehicule->setArchived($data['archived'] ?? false);

        if (isset($data['NumSerieCarburant'])) {
            $carburant = $this->entityManager->getRepository(Carburant::class)->find($data['NumSerieCarburant']);
            $vehicule->setNumSerieCarburant($carburant);
        }

        if (isset($data['NumeroAssurance'])) {
            $assurance = $this->entityManager->getRepository(Assurance::class)->find($data['NumeroAssurance']);
            $vehicule->setNumeroAssurance($assurance);
        }

        if (isset($data['idBudget'])) {
            $budget = $this->entityManager->getRepository(Budget::class)->find($data['idBudget']);
            $vehicule->setIdBudget($budget);
        }

        if (isset($data['entretient'])) {
            $entretient = $this->entityManager->getRepository(Entretient::class)->find($data['entretient']);
            $vehicule->setEntretient($entretient);
        }

        if (isset($data['departement'])) {
            $departement = $this->entityManager->getRepository(Departement::class)->find($data['departement']);
            $vehicule->setDepartement($departement);
        }


        $this->entityManager->persist($vehicule);
        $this->entityManager->flush();

        return $this->render('vehicule/create.html.twig', [
            'controller_name' => 'vehiculeController',
        ]);
    }

    /**
     * @Route("/vehicules/update", name="vehicule_update", methods={"PUT"})
     */
    public function update(Request $request, Vehicule $vehicule): Response
    {
        $data = json_decode($request->getContent(), true);

        $vehicule->setMarque($data['marque'] ?? $vehicule->getMarque());
        $vehicule->setModele($data['modele'] ?? $vehicule->getModele());
        $vehicule->setAnnee($data['annee'] ?? $vehicule->getAnnee());
        $vehicule->setImmatriculation($data['immatriculation'] ?? $vehicule->getImmatriculation());
        $vehicule->setEtat($data['etat'] ?? $vehicule->getEtat());
        $vehicule->setDescription($data['description'] ?? $vehicule->getDescription());
        $vehicule->setCouleur($data['couleur'] ?? $vehicule->getCouleur());
        $vehicule->setPrix($data['prix'] ?? $vehicule->getPrix());
        $vehicule->setNumeroSerie($data['NumeroSerie'] ?? $vehicule->getNumeroSerie());
        $vehicule->setKilometrage($data['kilometrage'] ?? $vehicule->getKilometrage());
        $vehicule->setType($data['type'] ?? $vehicule->getType());
        $vehicule->setDimensionRoue($data['dimensionRoue'] ?? $vehicule->getDimensionRoue());
        $vehicule->setDateDerniereVidange($data['dateDerniereVidange'] ? new \DateTime($data['dateDerniereVidange']) : $vehicule->getDateDerniereVidange());
        $vehicule->setCartePeage($data['cartePeage'] ?? $vehicule->getCartePeage());
        $vehicule->setArchived($data['archived'] ?? $vehicule->isArchived());

        if (isset($data['NumSerieCarburant'])) {
            $carburant = $this->entityManager->getRepository(Carburant::class)->find($data['NumSerieCarburant']);
            $vehicule->setNumSerieCarburant($carburant);
        }

        if (isset($data['NumeroAssurance'])) {
            $assurance = $this->entityManager->getRepository(Assurance::class)->find($data['NumeroAssurance']);
            $vehicule->setNumeroAssurance($assurance);
        }

        if (isset($data['idBudget'])) {
            $budget = $this->entityManager->getRepository(Budget::class)->find($data['idBudget']);
            $vehicule->setIdBudget($budget);
        }

        if (isset($data['entretient'])) {
            $entretient = $this->entityManager->getRepository(Entretient::class)->find($data['entretient']);
            $vehicule->setEntretient($entretient);
        }

        if (isset($data['departement'])) {
            $departement = $this->entityManager->getRepository(Departement::class)->find($data['departement']);
            $vehicule->setDepartement($departement);
        }

        $this->entityManager->flush();

        return $this->render('vehicule/update.html.twig', [
            'controller_name' => 'vehiculeController',
        ]);
    }

    /**
     * @Route("/vehicules/{id}/archive", name="vehicule_archive", methods={"PATCH"})
     */
    public function archive(Vehicule $vehicule): Response
    {
        $vehicule->setArchived(true);

        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
    /**
     * @Route("/vehicules", name="vehicule_archive", methods={"PATCH"})
     */
    public function index(VehiculeRepository $vehiculeRepository): Response
    {
        $vehicules = $vehiculeRepository->findAll();
        $alertes = [];

        foreach ($vehicules as $vehicule) {
            $alertes = array_merge($alertes, $vehicule->verifierAlertes());
        }
        return $this->render('vehicule/index.html.twig', [
            'alertes' => $alertes,
            'controller_name' => 'vehiculeController',
        ]);
    }
}
