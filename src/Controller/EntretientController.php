<?php

namespace App\Controller;

use App\Entity\Entretient;
use App\Entity\Vehicule;
use App\Repository\EntretientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntretientController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/entretient', name: 'entretient_index', methods: ['GET'])]
    public function index(EntretientRepository $entretientRepository): Response
    {
        $entretients = $entretientRepository->findAll();

        return $this->render('entretient/index.html.twig', [
            'entretients' => $entretients,
            'controller_name' => 'entretientController',
        ]);
    }

    /**
     * @Route("/entretient/show", name="entretient_show", methods={"GET"})
     */
    public function show(Entretient $entretient): Response
    {
        return $this->render('entretient/show.html.twig', [
            'controller_name' => 'entretientController',
        ]);
    }

    /**
     * @Route("/entretient/create", name="entretient_create", methods={"POST"})
     */
    public function create(Request $request): Response
{
    // Décoder le contenu JSON de la requête
    $data = json_decode($request->getContent(), true);

    // Créer une nouvelle instance de Entretient
    $entretient = new Entretient();

    // Vérifier et définir la date, en utilisant la date actuelle si aucune date n'est fournie
    $date = isset($data['date']) ? new \DateTime($data['date']) : new \DateTime();
    $entretient->setDate($date);

    // Vérifier et définir le type, en utilisant une chaîne vide comme valeur par défaut si 'type' est absent ou null
    $type = isset($data['type']) && is_string($data['type']) ? $data['type'] : '';
    $entretient->setType($type);

    // Vérifier et définir le prix, en utilisant 0.0 comme valeur par défaut si 'prix' est absent ou null
    $prix = isset($data['prix']) ? (float) $data['prix'] : 0.0;
    $entretient->setPrix($prix);

    // Vérifier et définir l'archive, en utilisant false comme valeur par défaut si 'archive' est absent ou null
    $archive = isset($data['archive']) && is_bool($data['archive']) ? $data['archive'] : false;
    $entretient->setArchive($archive);

    // Gestion des véhicules associés
    if (isset($data['vehicules'])) {
        foreach ($data['vehicules'] as $vehiculeId) {
            $vehicule = $this->entityManager->getRepository(Vehicule::class)->find($vehiculeId);
            if ($vehicule) {
                $entretient->addIdVehicule($vehicule);
            }
        }
    }

    // Persister et flusher l'entité
    $entityManager = $this->entityManager;
    $entityManager->persist($entretient);
    $entityManager->flush();

    // Retourner la réponse
    return $this->render('entretient/create.html.twig', [
        'controller_name' => 'EntretientController',
    ]);
}


    #[Route('/entretient/update', name: 'entretient_update', methods: ['PUT'])]
    public function update(Request $request, Entretient $entretient): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['date'])) {
            $entretient->setDate(new \DateTime($data['date']));
        }
        if (isset($data['type'])) {
            $entretient->setType($data['type']);
        }
        if (isset($data['prix'])) {
            $entretient->setPrix($data['prix']);
        }

        if (isset($data['vehicules'])) {
            foreach ($entretient->getIdVehicule() as $vehicule) {
                $entretient->removeIdVehicule($vehicule);
            }
            foreach ($data['vehicules'] as $vehiculeId) {
                $vehicule = $this->entityManager->getRepository(Vehicule::class)->find($vehiculeId);
                if ($vehicule) {
                    $entretient->addIdVehicule($vehicule);
                }
            }
        }

        $this->entityManager->flush();

        return $this->render('entretient/update.html.twig', [
            'controller_name' => 'entretientController',
        ]);
    }

    /**
     * @Route("/entretient/archive/{id}", name="entretient_archive")
     */
    public function archive(Entretient $entretient): Response
    {
        $entityManager = $this->entityManager;
        $entretient->setArchive(true);

        $entityManager->persist($entretient);
        $entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
