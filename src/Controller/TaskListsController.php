<?php

namespace App\Controller;

use App\Entity\TaskLists;
use App\Form\TaskListsType;
use App\Repository\TaskListsRepository;
use App\Repository\TasksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/task/lists')]
final class TaskListsController extends AbstractController
{
    #[Route(name: 'app_task_lists_index', methods: ['GET'])]
    public function index(TaskListsRepository $taskListsRepository): Response
    {
        return $this->render('task_lists/index.html.twig', [
            'task_lists' => $taskListsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_task_lists_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $taskList = new TaskLists();
        $form = $this->createForm(TaskListsType::class, $taskList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($taskList);
            $entityManager->flush();

            return $this->redirectToRoute('app_task_lists_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task_lists/new.html.twig', [
            'task_list' => $taskList,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_lists_show', methods: ['GET'])]
    public function show(TaskLists $taskList, TasksRepository $tasksRepository, PaginatorInterface $paginator): Response
    {
        $tasks  = $paginator->paginate($tasksRepository->getPaginatorQuery(['t.taskList' => $taskList]), 1);
        return $this->render('task_lists/show.html.twig', [
            'task_list' => $taskList,
            'tasks' => $tasks,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_task_lists_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TaskLists $taskList, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskListsType::class, $taskList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_task_lists_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('task_lists/edit.html.twig', [
            'task_list' => $taskList,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_lists_delete', methods: ['POST'])]
    public function delete(Request $request, TaskLists $taskList, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $taskList->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($taskList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_task_lists_index', [], Response::HTTP_SEE_OTHER);
    }
}
