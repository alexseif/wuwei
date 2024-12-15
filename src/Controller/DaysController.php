<?php

namespace App\Controller;

use App\Entity\Days;
use App\Form\DaysType;
use App\Repository\DaysRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/days')]
final class DaysController extends AbstractController
{
    #[Route(name: 'app_days_index', methods: ['GET'])]
    public function index(DaysRepository $daysRepository): Response
    {
        return $this->render('days/index.html.twig', [
            'days' => $daysRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_days_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $day = new Days();
        $form = $this->createForm(DaysType::class, $day);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($day);
            $entityManager->flush();

            return $this->redirectToRoute('app_days_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('days/new.html.twig', [
            'day' => $day,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_days_show', methods: ['GET'])]
    public function show(Days $day): Response
    {
        return $this->render('days/show.html.twig', [
            'day' => $day,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_days_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Days $day, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DaysType::class, $day);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_days_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('days/edit.html.twig', [
            'day' => $day,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_days_delete', methods: ['POST'])]
    public function delete(Request $request, Days $day, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$day->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($day);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_days_index', [], Response::HTTP_SEE_OTHER);
    }
}
