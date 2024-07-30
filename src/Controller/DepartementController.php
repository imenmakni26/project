<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Repository\DepartementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


class DepartementController extends AbstractController
{
    #[Route('/departement', name: 'app_departement')]
    public function index(DepartementRepository $departementRepository): Response
    {
        $departements = $departementRepository->findAll();

        return $this->render('departement/index.html.twig', [
            'departements' => $departements, 
        ]);
    }
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/departement/show", name="departement_show", methods={"GET"})
     */
    public function show(): Response
    {
        return $this->render('departement/show.html.twig', [
            'controller_name' => 'DepartementController',
        ]);
    }

    /**
     * @Route("/departement", name="departement_create", methods={"POST"})
     */
    public function create(Request $request): Response
{
    // Décoder le contenu JSON de la requête
    $data = json_decode($request->getContent(), true);

    // Créer une nouvelle instance de Departement
    $departement = new Departement();

    // Vérifier et définir le nom, en utilisant une chaîne vide comme valeur par défaut si 'nom' est absent ou null
    $nom = isset($data['nom']) && is_string($data['nom']) ? $data['nom'] : '';
    $departement->setNom($nom);

    // Vérifier et définir l'archive, en utilisant false comme valeur par défaut si 'archive' est absent ou null
    $archive = isset($data['archive']) && is_bool($data['archive']) ? $data['archive'] : false;
    $departement->setArchive($archive);

    // Persister et flusher l'entité
    $entityManager = $this->entityManager;
    $entityManager->persist($departement);
    $entityManager->flush();

    // Retourner la réponse
    return $this->render('departement/create.html.twig', [
        'controller_name' => 'DepartementController',
    ]);
}


    /**
     * @Route("/departement/update/{id}", name="departement_update", methods={"PUT"})
     */
    public function update(Request $request, Departement $departement): Response
    {
        $data = json_decode($request->getContent(), true);

        $departement->setNom($data['nom'] ?? $departement->getNom());
        $departement->setArchive($data['archive'] ?? $departement->isArchive());

        $entityManager = $this->entityManager;
        $entityManager->flush();

        return $this->render('departement/update.html.twig', [
            'controller_name' => 'DepartementController',
        ]);
    }

    /**
     * @Route("/departement/archive/{id}", name="departement_archive")
     */
    public function archive(Departement $departement): Response
    {
        $entityManager = $this->entityManager;
        $departement->setArchive(true);

        $entityManager->persist($departement);
        $entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
