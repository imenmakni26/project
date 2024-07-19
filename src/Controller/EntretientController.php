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
        ]);
    }

/**
     * @Route("/entretient/{id}", name="entretient_show", methods={"GET"})
     */
    public function show(Entretient $entretient): Response
    {
        return $this->json($entretient);
    }

    /**
     * @Route("/entretient", name="entretient_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $entretient = new Entretient();
        $entretient->setDate(new \DateTime($data['date'] ?? 'now')); // Utilisation de la date actuelle si aucune date n'est fournie
        $entretient->setType($data['type'] ?? null);
        $entretient->setPrix($data['prix'] ?? null);
        $entretient->setArchive($data['archive'] ?? false); // Valeur par défaut false

        // Gestion des véhicules associés
        if (isset($data['vehicules'])) {
            foreach ($data['vehicules'] as $vehiculeId) {
                $vehicule = $this->entityManager->getRepository(Vehicule::class)->find($vehiculeId);
                if ($vehicule) {
                    $entretient->addIdVehicule($vehicule);
                }
            }
        }

        $entityManager = $this->entityManager;
        $entityManager->persist($entretient);
        $entityManager->flush();

        return $this->json($entretient);
    }

    #[Route('/entretient/{id}', name: 'entretient_update', methods: ['PUT'])]
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

        return $this->json($entretient);
    }

    #[Route('/entretient/{id}', name: 'entretient_delete', methods: ['POST'])]
    public function archive(Entretient $entretient): Response
    {
        $entityManager = $this->entityManager;
        $entretient->setArchive(true);
    
        $entityManager->persist($entretient);
        $entityManager->flush();
    
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}