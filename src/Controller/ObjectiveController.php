<?php

namespace App\Controller;

use App\Entity\Objective;
use App\Form\ObjectiveType;
use App\Repository\ObjectiveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/objective')]
final class ObjectiveController extends AbstractController
{
    #[Route(name: 'app_objective_index', methods: ['GET'])]
    public function index(ObjectiveRepository $objectiveRepository): Response
    {
        return $this->render('objective/index.html.twig', [
            'objectives' => $objectiveRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_objective_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $objective = new Objective();
        $form = $this->createForm(ObjectiveType::class, $objective);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($objective);
            $entityManager->flush();

            return $this->redirectToRoute('app_objective_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('objective/new.html.twig', [
            'objective' => $objective,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_objective_show', methods: ['GET'])]
    public function show(Objective $objective): Response
    {
        return $this->render('objective/show.html.twig', [
            'objective' => $objective,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_objective_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Objective $objective, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ObjectiveType::class, $objective);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_objective_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('objective/edit.html.twig', [
            'objective' => $objective,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_objective_delete', methods: ['POST'])]
    public function delete(Request $request, Objective $objective, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$objective->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($objective);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_objective_index', [], Response::HTTP_SEE_OTHER);
    }
}
