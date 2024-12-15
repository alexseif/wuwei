<?php

namespace App\Controller;

use App\Entity\DashboardTaskLists;
use App\Form\DashboardTaskListsType;
use App\Repository\DashboardTaskListsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dashboard-tasklists')]
final class DashboardTaskListsController extends AbstractController
{
    #[Route(name: 'app_dashboard_task_lists_index', methods: ['GET'])]
    public function index(DashboardTaskListsRepository $dashboardTaskListsRepository): Response
    {
        return $this->render('dashboard_task_lists/index.html.twig', [
            'dashboard_task_lists' => $dashboardTaskListsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_dashboard_task_lists_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dashboardTaskList = new DashboardTaskLists();
        $form = $this->createForm(DashboardTaskListsType::class, $dashboardTaskList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dashboardTaskList);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_task_lists_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard_task_lists/new.html.twig', [
            'dashboard_task_list' => $dashboardTaskList,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dashboard_task_lists_show', methods: ['GET'])]
    public function show(DashboardTaskLists $dashboardTaskList): Response
    {
        return $this->render('dashboard_task_lists/show.html.twig', [
            'dashboard_task_list' => $dashboardTaskList,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dashboard_task_lists_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DashboardTaskLists $dashboardTaskList, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DashboardTaskListsType::class, $dashboardTaskList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_task_lists_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard_task_lists/edit.html.twig', [
            'dashboard_task_list' => $dashboardTaskList,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dashboard_task_lists_delete', methods: ['POST'])]
    public function delete(Request $request, DashboardTaskLists $dashboardTaskList, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dashboardTaskList->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($dashboardTaskList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dashboard_task_lists_index', [], Response::HTTP_SEE_OTHER);
    }
}
