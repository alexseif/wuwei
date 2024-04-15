<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/task')]
class TaskController extends AbstractController
{

    #[Route('/', name: 'app_task_index', methods: ['GET'])]
    public function index(
      TaskRepository $taskRepository,
      string $viewType = 'accordion'
    ): Response {
        return $this->render('task/index.html.twig', [
          'viewType' => $viewType,
          'tasks' => $taskRepository->findAll(),
        ]);
    }

    #[Route('/view/{viewType?}', name: 'app_task_index_view', methods: ['GET'], defaults: ['viewType' => 'accordion'])]
    public function view(
      TaskRepository $taskRepository,
      string $viewType = 'accordion'
    ): Response {
        return $this->render('task/index.html.twig', [
          'viewType' => $viewType,
          'tasks' => $taskRepository->findAll(),
        ]);
    }

    #[Route('/new/{tasklist}', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new(
      Request $request,
      EntityManagerInterface $entityManager,
      Tag $tasklist = null
    ): Response {
        $task = new Task();
        if ($tasklist) {
            $task->addTag($tasklist);
        }
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Account Tag
            $accountTag = $form->get('account')->getData();
            if ($accountTag && !$task->getTags()->contains($accountTag)) {
                $task->addTag($accountTag);
            }
            $entityManager->persist($task);
            $entityManager->flush();
            $response = $this->redirectToRoute(
              'app_task_index',
              [],
              Response::HTTP_SEE_OTHER
            );
            if ($tasklist) {
                $response = $this->redirectToRoute(
                  'app_tasklist_show',
                  ['id' => $tasklist->getId()],
                  Response::HTTP_SEE_OTHER
                );
            }
            return $response;
        }

        return $this->renderForm('task/new.html.twig', [
          'task' => $task,
          'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
          'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(
      Request $request,
      Task $task,
      EntityManagerInterface $entityManager
    ): Response {
        //TODO: Load account tag
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Account Tag
            $accountTag = $form->get('account')->getData();
            if ($accountTag && !$task->getTags()->contains($accountTag)) {
                $task->addTag($accountTag);
            }

            $entityManager->flush();

            return $this->redirectToRoute(
              'app_task_index',
              [],
              Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('task/edit.html.twig', [
          'task' => $task,
          'form' => $form,
        ]);
    }

    #[Route('/{id}/eisenhower', name: 'app_task_eisenhower', methods: [
      'GET',
      'POST',
    ])]
    public function eisenhower(
      Request $request,
      Task $task,
      EntityManagerInterface $entityManager
    ): Response {
        //Check if request is ajax
        if (!$request->isXmlHttpRequest()) {
            return $this->redirectToRoute(
              'app_task_index',
              [],
              Response::HTTP_SEE_OTHER
            );
        }
        //Get task from jquery draggable ajax request
        // update priority and urgency
        $task->setPriority($request->get('priority'));
        $task->setUrgency($request->get('urgency'));
        $entityManager->flush();
        //Return response to jquery draggable ajax request
        return new Response('success');
    }

    #[Route('/{id}', name: 'app_task_delete', methods: ['POST'])]
    public function delete(
      Request $request,
      Task $task,
      EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid(
          'delete' . $task->getId(),
          $request->request->get('_token')
        )) {
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute(
          'app_task_index',
          [],
          Response::HTTP_SEE_OTHER
        );
    }

}
