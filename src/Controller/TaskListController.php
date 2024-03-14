<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Form\TasklistType;
use App\Repository\TagRepository;
use App\Repository\TagTypeRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tasklist')]
class TaskListController extends AbstractController
{

    #[Route('/', name: 'app_tasklist_index')]
    public function index(
      TagTypeRepository $tagTypeRepository,
      TagRepository $tagRepository,
      TaskRepository $taskRepository
    ): Response {
        $taskListTagType = $tagTypeRepository->findOneBy(
          ['name' => 'Task List']
        );
        $taskLists = $tagRepository->findBy(['tagType' => $taskListTagType]);
        return $this->render('tasklist/index.html.twig', [
          'taskListTagType' => $taskListTagType,
          'tasklists' => $taskLists,
        ]);
    }

    #[Route('/new', name: 'app_tasklist_new', methods: ['GET', 'POST'])]
    public function new(
      Request $request,
      EntityManagerInterface $entityManager,
      TagTypeRepository $tagTypeRepository
    ): Response {
        $taskListTagType = $tagTypeRepository->findOneBy(
          ['name' => 'Task List']
        );
        $tasklist = new Tag();
        $tasklist->setTagType($taskListTagType);
        $form = $this->createForm(TasklistType::class, $tasklist);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tasklist);
            $entityManager->flush();

            return $this->redirectToRoute(
              'app_tasklist_show',
              ['id' => $tasklist->getId()],
              Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('tasklist/new.html.twig', [
          'tag' => $tasklist,
          'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tasklist_show', methods: ['GET'])]
    public function show(
      Tag $tasklist,
      TaskRepository $taskRepository
    ): Response {
        $tasks = $taskRepository->findByTag($tasklist);
        return $this->render('tasklist/show.html.twig', [
          'tasklist' => $tasklist,
          'tasks' => $tasks,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tasklist_edit', methods: ['GET', 'POST'])]
    public function edit(
      Request $request,
      Tag $tasklist,
      EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(TasklistType::class, $tasklist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute(
              'app_tasklist_index',
              [],
              Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('tasklist/edit.html.twig', [
          'tasklist' => $tasklist,
          'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tasklist_delete', methods: ['POST'])]
    public function delete(
      Request $request,
      Tag $tasklist,
      EntityManagerInterface $entityManager,
      TaskRepository $taskRepository
    ): Response {
        if ($this->isCsrfTokenValid(
          'delete' . $tasklist->getId(),
          $request->request->get('_token')
        )) {
            $tasks = $taskRepository->findByTag($tasklist);
            foreach ($tasks as $task) {
                $entityManager->remove($task);
            }
            $entityManager->remove($tasklist);
            $entityManager->flush();
        }

        return $this->redirectToRoute(
          'app_tasklist_index',
          [],
          Response::HTTP_SEE_OTHER
        );
    }


}
