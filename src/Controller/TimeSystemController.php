<?php

namespace App\Controller;

use App\Entity\TimeSystem;
use App\Form\TimeSystemType;
use App\Repository\TimeSystemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/time/system')]
class TimeSystemController extends AbstractController
{
    #[Route('/', name: 'app_time_system_index', methods: ['GET'])]
    public function index(TimeSystemRepository $timeSystemRepository): Response
    {
        return $this->render('time_system/index.html.twig', [
            'time_systems' => $timeSystemRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_time_system_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $timeSystem = new TimeSystem();
        $form = $this->createForm(TimeSystemType::class, $timeSystem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($timeSystem);
            $entityManager->flush();

            return $this->redirectToRoute('app_time_system_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('time_system/new.html.twig', [
            'time_system' => $timeSystem,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_time_system_show', methods: ['GET'])]
    public function show(TimeSystem $timeSystem): Response
    {
        return $this->render('time_system/show.html.twig', [
            'time_system' => $timeSystem,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_time_system_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TimeSystem $timeSystem, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TimeSystemType::class, $timeSystem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_time_system_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('time_system/edit.html.twig', [
            'time_system' => $timeSystem,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_time_system_delete', methods: ['POST'])]
    public function delete(Request $request, TimeSystem $timeSystem, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$timeSystem->getId(), $request->request->get('_token'))) {
            $entityManager->remove($timeSystem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_time_system_index', [], Response::HTTP_SEE_OTHER);
    }
}
