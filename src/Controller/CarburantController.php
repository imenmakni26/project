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
     * @Route("/carburant/show", name="carburant_show")
     */
    public function show(): Response
    {
        return $this->render('carburant/show.html.twig', [
            'controller_name' => 'carburantController',
        ]);
    }

    /**
     * @Route("/carburant/create", name="carburant_create", methods={"POST"})
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

        return $this->render('carburant/create.html.twig', [
            'controller_name' => 'carburantController',
        ]);
    }

    /**
     * @Route("/carburant/update", name="carburant_update", methods={"PUT"})
     */
    public function update(Request $request, Carburant $carburant): Response
    {
        // Assurez-vous que les données sont envoyées en JSON
        $data = json_decode($request->getContent(), true);

        // Utilisation de json_decode pour récupérer les données
        $numserie = $data['numserie'] ?? 'default_value';
        $valeur = $data['valeur'] ?? 'default_value';
        $motDePasse = $data['motDePasse'] ?? 'default_value';
        $archive = $data['archive'] ?? false;

        // Vérifiez que numserie, valeur et motDePasse sont des chaînes
        if (is_string($numserie)) {
            $carburant->setNumserie($numserie);
        }

        if (is_string($valeur)) {
            $carburant->setValeur($valeur);
        }

        if (is_string($motDePasse)) {
            $carburant->setMotDePasse($motDePasse);
        }

        // Assurez-vous que $archive est un booléen
        $carburant->setArchive((bool)$archive);

        // Sauvegarde de l'entité
        $this->entityManager->flush();

        // Retourne une réponse ou une vue appropriée
        return $this->render('carburant/update.html.twig', [
            'controller_name' => 'CarburantController',
        ]);
    }

    /**
     * @Route("/departement/archive/{id}", name="carburant_archive")
     */
    public function archive(Carburant $carburant): Response
    {
        $entityManager = $this->entityManager;
        $carburant->setArchive(true);

        $entityManager->persist($carburant);
        $entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
