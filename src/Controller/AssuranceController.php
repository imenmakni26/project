<?php

namespace App\Controller;

use App\Entity\Assurance;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssuranceController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/assurance', name: 'assurance_index')]
    public function index(): Response
    {
        return $this->render('assurance/index.html.twig', [
            'controller_name' => 'AssuranceController',
        ]);
    }
    
     /**
     * @Route("/assurance/show/{id}", name="assurance_show")
     */
    public function show(Assurance $assurance): Response
    {
        return $this->render('assurance/show.html.twig', [
            'assurance' => $assurance,
        ]);
    }

    /**
     * @Route("/assurance/create", name="assurance_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $numero = $data['numero'] ?? null;
        $type = $data['type'] ?? null;
        $agence = $data['agence'] ?? null;
        $dateString = $data['date'] ?? null;
        $archive = $data['archive'] ?? false;

        if ($numero === null || $type === null || $agence === null || $dateString === null) {
            return $this->render('assurance/create.html.twig', [
                'controller_name' => 'AssuranceController',
            ]);
        }

        $assurance = new Assurance();
        $assurance->setNumero($numero);
        $assurance->setType($type);
        $assurance->setAgence($agence);

        $date = \DateTime::createFromFormat('Y-m-d', $dateString);
        if ($date === false) {
            return new Response('Date invalide', Response::HTTP_BAD_REQUEST);
        }
        $assurance->setDate($date);
        $assurance->setArchive($archive);

        $entityManager->persist($assurance);
        $entityManager->flush();

        return $this->render('assurance/create.html.twig', [
            'controller_name' => 'AssuranceController',
        ]);
    }

    
    

    /**
     * @Route("/assurance/update/{id}", name="assurance_update", methods={"GET"})
     */
    public function update(Request $request, Assurance $assurance): Response
    {
        $data = json_decode($request->getContent(), true);
        $numero = $request->request->get('numero');
        $type = $request->request->get('type');
        $agence = $request->request->get('agence');
        $date = $request->request->get('date');
        $archive = $request->request->get('archive');
        $prix = $request->request->get('prix');

        if ($numero !== null && is_string($numero)) {
            $assurance->setNumero($numero);
        } else {
            $assurance->setNumero('default_value'); 
        }

        if ($type !== null && is_string($type)) {
            $assurance->setType($type);
        } else {
            $assurance->setType('default_value'); 
        }
        
        if ($agence !== null && is_string($agence)) {
            $assurance->setAgence($agence);
        } else {
            $assurance->setAgence('default_value'); 
        }

        if ($date !== null && strtotime($date) !== false) {
            $assurance->setDate(new \DateTime($date));
        } else {
            $assurance->setDate(new \DateTime('now')); 
        }

        if ($archive !== null && in_array($archive, ['1', '0', 'true', 'false', true, false], true)) {
            $assurance->setArchive(filter_var($archive, FILTER_VALIDATE_BOOLEAN));
        } else {
            $assurance->setArchive(false); 
        }

        if ($prix !== null && is_numeric($prix)) {
            $assurance->setPrix((float)$prix);
        } else {
            $assurance->setPrix(0.0); 
        }

        $entityManager = $this->entityManager;
        $entityManager->flush();

        return $this->render('assurance/update.html.twig', [
            'controller_name' => 'AssuranceController',
        ]);
    }

    /**
     * @Route("/assurance/archive/{id}", name="assurance_archive")
     */
    public function archive(Assurance $assurance): Response
    {
        $assurance->setArchive(true);
        $this->entityManager->persist($assurance);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}