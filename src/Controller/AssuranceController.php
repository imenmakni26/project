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
     * @Route("/assurance/{id}", name="assurance_show", requirements={"id"="\d+"})
     */
    public function show(int $id): Response
    {
        $assurance = $this->entityManager->getRepository(Assurance::class)->find($id);

        if (!$assurance) {
            throw $this->createNotFoundException('Aucune assurance trouvée pour cet ID');
        }

        return $this->render('assurance/show.html.twig', [
            'assurance' => $assurance,
        ]);
    }

    /**
     * @Route("/assurance", name="assurance_create")
     */
    public function create(Request $request,EntityManagerInterface $entityManager): Response
    {
        if($request->isMethod('POST')){
            
        $data = json_decode($request->getContent(), true);
        $numero= $request->get('numero');
        $type= $request->get('type');
        $agence= $request->get('agence');
        $date = $request->get('date');
        $archive= $request->get('archive');

        $assurance = new Assurance();
        $assurance->setNumero($numero);
        $assurance->setType($type);
        $assurance->setAgence($agence);
        $assurance->setDate($date);
        $assurance->setArchive($archive);
        
        $entityManager = $this->entityManager;
        $entityManager->persist($assurance);
        $entityManager->flush();
        return new Response('Assurance créée avec succès');

        }
        return $this->render('assurance/create.html.twig', [
            'controller_name' => 'AssuranceController',
        ]);
    }
    
    

    /**
     * @Route("/assurance/{id}", name="assurance_update", methods={"GET"})
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