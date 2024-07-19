<?php

namespace App\Controller;

use App\Entity\Entretient;
use App\Form\EntretientType;
use App\Repository\EntretientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntretientController extends AbstractController
{
    #[Route('/entretient', name: 'entretient_index', methods: ['GET'])]
    public function index(EntretientRepository $entretientRepository): Response
    {
        $entretients = $entretientRepository->findAll();

        return $this->render('entretient/index.html.twig', [
            'entretients' => $entretients,
        ]);
    }

    #[Route('/entretient/new', name: 'entretient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entretient = new Entretient();
        $form = $this->createForm(Entretient::class, $entretient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entretient);
            $entityManager->flush();

            return $this->redirectToRoute('entretient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('entretient/new.html.twig', [
            'entretient' => $entretient,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/entretient/{id}', name: 'entretient_show', methods: ['GET'])]
    public function show(Entretient $entretient): Response
    {
        return $this->render('entretient/show.html.twig', [
            'entretient' => $entretient,
        ]);
    }

    #[Route('/entretient/{id}/edit', name: 'entretient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entretient $entretient, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Entretient::class, $entretient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('entretient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('entretient/edit.html.twig', [
            'entretient' => $entretient,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/entretient/{id}', name: 'entretient_delete', methods: ['POST'])]
    public function delete(Request $request, Entretient $entretient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entretient->getId(), $request->request->get('_token'))) {
            $entityManager->remove($entretient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('entretient_index', [], Response::HTTP_SEE_OTHER);
    }
}