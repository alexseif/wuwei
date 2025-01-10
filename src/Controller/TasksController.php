<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Entity\TaskLists;
use App\Form\TasksType;
use App\Repository\TasksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tasks')]
final class TasksController extends AbstractController
{
    #[Route(name: 'app_tasks_index', methods: ['GET'])]
    public function index(
        Request $request,
        TasksRepository $tasksRepository,
        PaginatorInterface $paginator
    ): Response {
        $completed = $request->query->getBoolean('completed') ?? false;
        $criteria = ['t.completed' => $completed];

        $pagination = $paginator->paginate(
            $tasksRepository->getPaginatorQuery($criteria),
            $request->query->getInt('page', 1)
        );

        return $this->render('tasks/index.html.twig', [
            'completed' => $completed,
            'tasks' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_tasks_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Tasks();
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();
            $this->addFlash('success', 'Task created');

            return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tasks/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tasks_show', methods: ['GET'])]
    public function show(Tasks $task): Response
    {
        return $this->render('tasks/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tasks_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tasks $task, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Task updated');

            return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tasks/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/completed', name: 'app_tasks_completed', methods: ['GET', 'POST'])]
    public function completed(Request $request, Tasks $task, EntityManagerInterface $entityManager): Response
    {
        $completed = $request->getPayload()->getBoolean('completed');
        $duration = $request->getPayload()->getInt('duration');
        $task->setCompleted($completed);
        $task->setDuration($duration);
        if ($completed) {
            $task->setCompletedAt(new \DateTime());
        } else {
            $task->setCompletedAt(null);
        }
        $entityManager->flush();

        return new JsonResponse(['task' => $task->toArray()]);
    }

    #[Route('/{id}/order', name: 'app_tasks_order', methods: ['GET', 'POST'])]
    public function order(Request $request, Tasks $task, EntityManagerInterface $entityManager): Response
    {
        $order = $request->getPayload()->getInt('updatedOrder');
        $task->setOrder($order);

        $entityManager->flush();
        return new JsonResponse(['task' => $task->toArray()]);
    }

    #[Route('/{id}', name: 'app_tasks_delete', methods: ['POST'])]
    public function delete(Request $request, Tasks $task, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
            $this->addFlash('success', 'Task Deleted');
        }

        return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
    }
}
