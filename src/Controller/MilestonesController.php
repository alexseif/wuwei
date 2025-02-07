<?php

namespace App\Controller;

use App\Entity\Milestones;
use App\Form\MilestonesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/milestones')]
final class MilestonesController extends AbstractController
{
    #[Route(name: 'app_milestones_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $milestones = $entityManager
            ->getRepository(Milestones::class)
            ->findAll();

        return $this->render('milestones/index.html.twig', [
            'milestones' => $milestones,
        ]);
    }

    #[Route('/new', name: 'app_milestones_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $milestone = new Milestones();
        $form = $this->createForm(MilestonesType::class, $milestone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($milestone);
            $entityManager->flush();

            return $this->redirectToRoute('app_milestones_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('milestones/new.html.twig', [
            'milestone' => $milestone,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_milestones_show', methods: ['GET'])]
    public function show(Milestones $milestone): Response
    {
        return $this->render('milestones/show.html.twig', [
            'milestone' => $milestone,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_milestones_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Milestones $milestone, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MilestonesType::class, $milestone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_milestones_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('milestones/edit.html.twig', [
            'milestone' => $milestone,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_milestones_delete', methods: ['POST'])]
    public function delete(Request $request, Milestones $milestone, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $milestone->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($milestone);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_milestones_index', [], Response::HTTP_SEE_OTHER);
    }
}
