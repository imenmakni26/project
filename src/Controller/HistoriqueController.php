<?php

namespace App\Controller;

use App\Entity\Historique;
use App\Entity\Vehicule;
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
     * @Route("/historique/show", name="historique_show", methods={"GET"})
     */
    public function show(Historique $historique): Response
    {
        return $this->render('historique/show.html.twig', [
            'controller_name' => 'historiqueController',
        ]);
    }
    /**
     * @Route("/historique/create", name="historique_create")
     */
    public function create(Request $request): Response
{
    $data = json_decode($request->getContent(), true);

    $historique = new Historique();

    $date = isset($data['date']) ? new \DateTime($data['date']) : new \DateTime();
    $historique->setDate($date);

    $description = isset($data['description']) ? (string) $data['description'] : '';
    $historique->setDescription($description);

    $cout = isset($data['cout']) ? (float) $data['cout'] : 0.0;
    $historique->setCout($cout);

    $vehicule = isset($data['vehicule']) ? $this->entityManager->getRepository(Vehicule::class)->find($data['vehicule']) : null;
    $historique->setVehicule($vehicule);

    $archive = isset($data['archive']) && is_bool($data['archive']) ? $data['archive'] : false;
    $historique->setArchive($archive);

    $entityManager = $this->entityManager;
    $entityManager->persist($historique);
    $entityManager->flush();

    return $this->render('historique/create.html.twig', [
        'controller_name' => 'HistoriqueController',
    ]);
}


    /**
     * @Route("/historique/update/{id}", name="historique_update")
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

        return $this->render('historique/update.html.twig', [
            'controller_name' => 'historiqueController',
        ]);
    }

    /**
     * @Route("historique/archive/{id}", name="historique_archive")
     */
    public function archive(Historique $historique): Response
    {
        $entityManager = $this->entityManager;
        $historique->setArchive(true);

        $entityManager->persist($historique);
        $entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
