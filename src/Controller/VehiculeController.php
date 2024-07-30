<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Entity\Assurance;
use App\Entity\Budget;
use App\Entity\Carburant;
use App\Entity\Entretient;
use App\Entity\Departement;
use App\Entity\Historique;
use App\Entity\Directeur;
use App\Entity\DirecteurCommercial;
use App\Entity\Responsabledeflotte;
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
    public function show(): Response
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

    // Assurez-vous que 'marque' est une chaîne de caractères ou une valeur par défaut
    $vehicule->setMarque($data['marque'] ?? '');

    // Assurez-vous que 'modele' est une chaîne de caractères ou une valeur par défaut
    $vehicule->setModele($data['modele'] ?? '');

    // Assurez-vous que 'annee' est un entier ou une valeur par défaut
    $vehicule->setAnnee($data['annee'] ?? 0);

    // Assurez-vous que 'immatriculation' est une chaîne de caractères ou une valeur par défaut
    $vehicule->setImmatriculation($data['immatriculation'] ?? '');

    // Assurez-vous que 'etat' est une chaîne de caractères ou une valeur par défaut
    $vehicule->setEtat($data['etat'] ?? '');

    // Assurez-vous que 'description' est une chaîne de caractères ou une valeur par défaut
    $vehicule->setDescription($data['description'] ?? '');

    // Assurez-vous que 'couleur' est une chaîne de caractères ou une valeur par défaut
    $vehicule->setCouleur($data['couleur'] ?? '');

    // Assurez-vous que 'prix' est un float ou une valeur par défaut
    $vehicule->setPrix($data['prix'] ?? 0.0);

    // Assurez-vous que 'NumeroSerie' est une chaîne de caractères ou une valeur par défaut
    $vehicule->setNumeroSerie($data['NumeroSerie'] ?? '');

    // Assurez-vous que 'kilometrage' est un entier ou une valeur par défaut
    $vehicule->setKilometrage($data['kilometrage'] ?? 0);

    // Assurez-vous que 'type' est une chaîne de caractères ou une valeur par défaut
    $vehicule->setType($data['type'] ?? '');

    // Assurez-vous que 'dimensionRoue' est un float ou une valeur par défaut
    $vehicule->setDimensionRoue($data['dimensionRoue'] ?? 0.0);

    // Assurez-vous que 'dateDerniereVidange' est une instance de \DateTimeInterface ou null
    if (isset($data['dateDerniereVidange']) && !empty($data['dateDerniereVidange'])) {
        try {
            $dateDerniereVidange = new \DateTime($data['dateDerniereVidange']);
        } catch (\Exception $e) {
            // Gestion des erreurs si la date est invalide
            $dateDerniereVidange = null;
        }
    } else {
        $dateDerniereVidange = null;
    }
    $vehicule->setDateDerniereVidange($dateDerniereVidange);

    // Assurez-vous que 'cartePeage' est une chaîne de caractères ou une valeur par défaut
    $vehicule->setCartePeage($data['cartePeage'] ?? '');

    // Assurez-vous que 'archived' est un booléen
    $vehicule->setArchived($data['archived'] ?? false);

    // Traitement des relations
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

    if (isset($data['historiques'])) {
        foreach ($data['historiques'] as $historiqueId) {
            $historique = $this->entityManager->getRepository(Historique::class)->find($historiqueId);
            if ($historique) {
                $vehicule->addHistorique($historique);
            }
        }
    }

    if (isset($data['ResponsableDeFlotte'])) {
        $responsableDeFlotte = $this->entityManager->getRepository(ResponsableDeFlotte::class)->find($data['ResponsableDeFlotte']);
        $vehicule->setResponsabledeflotte($responsableDeFlotte);
    }

    if (isset($data['DirecteurCommercial'])) {
        $directeurCommercial = $this->entityManager->getRepository(DirecteurCommercial::class)->find($data['DirecteurCommercial']);
        $vehicule->setDirecteurCommercial($directeurCommercial);
    }

    if (isset($data['Directeur'])) {
        $directeur = $this->entityManager->getRepository(Directeur::class)->find($data['Directeur']);
        $vehicule->setDirecteur($directeur);
    }

    // Persister l'entité
    $this->entityManager->persist($vehicule);
    $this->entityManager->flush();

    return $this->render('vehicule/create.html.twig', [
        'controller_name' => 'VehiculeController',
    ]);
}





    /**
     * @Route("/vehicules/update/{id}", name="vehicule_update", methods={"PUT"})
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
        if (isset($data['ResponsableDeFlotte'])) {
            $ResponsableDeFlotte = $this->entityManager->getRepository(ResponsableDeFlotte::class)->find($data['ResponsableDeFlotte']);
            $vehicule->setResponsableDeFlotte($ResponsableDeFlotte);
        }
        if (isset($data['DirecteurCommercial'])) {
            $DirecteurCommercial = $this->entityManager->getRepository(DirecteurCommercial::class)->find($data['DirecteurCommercial']);
            $vehicule->setDirecteurCommercial($DirecteurCommercial);
        }
        if (isset($data['Directeur'])) {
            $Directeur = $this->entityManager->getRepository(Directeur::class)->find($data['Directeur']);
            $vehicule->setDirecteur($Directeur);
        }

        $this->entityManager->flush();

        return $this->render('vehicule/update.html.twig', [
            'controller_name' => 'vehiculeController',
        ]);
    }

    /**
     * @Route("/vehicules/archive/{id}", name="vehicule_archive", methods={"PATCH"})
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
