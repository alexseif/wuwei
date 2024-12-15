<?php

namespace App\Controller;

use App\Entity\WorkLog;
use App\Form\WorkLogType;
use App\Repository\WorkLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/work/log')]
final class WorkLogController extends AbstractController
{
    #[Route(name: 'app_work_log_index', methods: ['GET'])]
    public function index(WorkLogRepository $workLogRepository): Response
    {
        return $this->render('work_log/index.html.twig', [
            'work_logs' => $workLogRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_work_log_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $workLog = new WorkLog();
        $form = $this->createForm(WorkLogType::class, $workLog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($workLog);
            $entityManager->flush();

            return $this->redirectToRoute('app_work_log_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('work_log/new.html.twig', [
            'work_log' => $workLog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_work_log_show', methods: ['GET'])]
    public function show(WorkLog $workLog): Response
    {
        return $this->render('work_log/show.html.twig', [
            'work_log' => $workLog,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_work_log_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WorkLog $workLog, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WorkLogType::class, $workLog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_work_log_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('work_log/edit.html.twig', [
            'work_log' => $workLog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_work_log_delete', methods: ['POST'])]
    public function delete(Request $request, WorkLog $workLog, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$workLog->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($workLog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_work_log_index', [], Response::HTTP_SEE_OTHER);
    }
}
