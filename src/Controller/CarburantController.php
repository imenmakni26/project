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

    #[Route('/carburant', name:'carburant_index')]
    public function index(): Response
    {
        return $this->render('carburant/index.html.twig',[
            'controller_name' => 'CarburantController',
        ]);
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
        //$data = json_decode($request->getContent(), true);

        $carburant = new Carburant();

        //$numserie = $data['numserie'] ?? '';
        $valeurString = $request->get('valeur');
        $motDePasse = $request->get('motDePasse');
        $archive = $request->get('archive');

        if ($request->get('numserie') !== null) {

        $carburant->setNumserie($request->get('numserie'));
        }

        if ($valeurString !== null) {

        $carburant->setValeur($valeurString);
        }

        if ($valeurString !== null) {

            $carburant->setMotDePasse($motDePasse);
        }

        if ($valeurString !== null) {

            $carburant->setArchive($archive);
        }
    
        $entityManager = $this->entityManager;
        $entityManager->persist($carburant);
        $entityManager->flush();

        return $this->render('carburant/create.html.twig', [
            'controller_name' => 'CarburantController',
        ]);
    }
    

    /**
     * @Route("/carburant/update", name="carburant_update", methods={"PUT"})
     * @ParamConverter("carburant", class="App\Entity\Carburant")

     */
    public function update(Carburant $carburant, Request $request): Response
    {
        // Logique de mise à jour
        $data = $request->request->all();

        $numserie = $data['numserie'] ?? $carburant->getNumserie();
        $valeur = $data['valeur'] ?? $carburant->getValeur();
        $motDePasse = $data['motDePasse'] ?? $carburant->getMotDePasse();
        $archive = isset($data['archive']) ? (bool)$data['archive'] : $carburant->isArchive();

        $carburant->setNumserie($numserie);
        $carburant->setValeur($valeur);
        $carburant->setMotDePasse($motDePasse);
        $carburant->setArchive($archive);

        try {
            $this->entityManager->flush();
        } catch (\Exception $e) {
            return new Response('Erreur lors de la mise à jour : ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->render('carburant/update.html.twig', [
            'carburant' => $carburant,
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
