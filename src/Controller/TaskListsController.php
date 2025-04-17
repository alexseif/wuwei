<?php

namespace App\Controller;

use App\Entity\TaskLists;
use App\Form\TaskListsType;
use App\Repository\AccountsRepository;
use App\Repository\TaskListsRepository;
use App\Repository\TasksRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tasklists')]
final class TaskListsController extends AbstractController
{
    private array $twigParts = [
        'entity_name' => 'task_lists',
        'entity_title' => 'Task List'
    ];

    #[Route('/new', name: 'app_task_lists_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, AccountsRepository $accountsRepository): Response
    {
        $taskList = new TaskLists();
        $accountId = $request->get('account') ?? null;
        if ($accountId) {
            $account = $accountsRepository->find($accountId);
            $taskList->setAccount($account);
        }
        $form = $this->createForm(TaskListsType::class, $taskList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($taskList);
            $entityManager->flush();

            $this->addFlash('success', 'Task List added');
            if ($accountId) {
                return $this->redirectToRoute('app_accounts_show', ['id' => $accountId]);
            }
            return $this->redirectToRoute('app_task_lists_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->twigParts['task_list'] = $taskList;
        $this->twigParts['entity'] = $taskList;
        $this->twigParts['form'] = $form;
        return $this->render('task_lists/new.html.twig', $this->twigParts);
    }

    #[Route('/update-order', name: 'app_task_lists_update_order', methods: ['POST'])]
    public function updateOrder(Request $request, TaskListsRepository $taskListsRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['order']) || !is_array($data['order'])) {
            return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }

        $caseStatements = [];
        $ids = []; // Collect IDs for the WHERE clause

        foreach ($data['order'] as $index => $id) {
            $caseStatements[] = "WHEN tl.id = :id{$index} THEN {$index}";
            $ids[] = $id; // Collect IDs
        }

        $caseSql = implode(' ', $caseStatements);

        // Build the query
        $query = $entityManager->createQuery(
            "UPDATE App\Entity\TaskLists tl
             SET tl.order = CASE {$caseSql} ELSE tl.order END
             WHERE tl.id IN (:ids)"
        );

        // Bind parameters
        foreach ($data['order'] as $index => $id) {
            $query->setParameter("id{$index}", $id);
        }
        $query->setParameter('ids', $ids);

        // Execute the query
        $query->execute();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/{id}', name: 'app_task_lists_show', methods: ['GET'])]
    #[Route(name: 'app_task_lists_index', methods: ['GET'])]
    public function index(
        Request $request,
        TaskListsRepository $taskListsRepository,
        TasksRepository $tasksRepository,
        PaginatorInterface $paginator,
        TaskLists $taskList = null
    ): Response {

        $this->twigParts['task_lists'] = $taskListsRepository->findAll();

        if ($taskList) {
            $tasks  = $paginator->paginate($tasksRepository->getPaginatorQuery(['t.taskList' => $taskList]), $request->query->getInt('page', 1));
            $progress = $tasksRepository->getProgressByTasklist($taskList);

            $this->twigParts['task_list'] = $taskList;
            $this->twigParts['entity'] = $taskList;
            $this->twigParts['tasks'] = $tasks;
            $this->twigParts['progress'] = $progress;
        }
        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView('task_lists/_show.html.twig', $this->twigParts);
            return new JsonResponse($content);
        }


        return $this->render('task_lists/index.html.twig', $this->twigParts);
    }

    #[Route('/{id}', name: 'app_task_lists_show_back', methods: ['GET'])]
    public function show(Request $request, TaskLists $taskList, TasksRepository $tasksRepository, PaginatorInterface $paginator): Response
    {
        $tasks  = $paginator->paginate($tasksRepository->getPaginatorQuery(['t.taskList' => $taskList]), $request->query->getInt('page', 1));

        $this->twigParts['task_list'] = $taskList;
        $this->twigParts['entity'] = $taskList;
        $this->twigParts['tasks'] = $tasks;

        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView('task_lists/_show.html.twig', $this->twigParts);
            return new JsonResponse($content);
        }

        return $this->render('task_lists/show.html.twig', $this->twigParts);
    }

    #[Route('/{id}/edit', name: 'app_task_lists_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TaskLists $taskList, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskListsType::class, $taskList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Task List Updated');

            return $this->redirectToRoute('app_task_lists_index', [], Response::HTTP_SEE_OTHER);
        }
        $this->twigParts['task_list'] = $taskList;
        $this->twigParts['entity'] = $taskList;
        $this->twigParts['form'] = $form;
        return $this->render('task_lists/edit.html.twig', $this->twigParts);
    }

    #[Route('/{id}', name: 'app_task_lists_delete', methods: ['POST'])]
    public function delete(Request $request, TaskLists $taskList, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $taskList->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($taskList);
            $entityManager->flush();
            $this->addFlash('success', 'Task List Deleted');
        }

        return $this->redirectToRoute('app_task_lists_index', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/{id}/demote', name: 'app_task_lists_demote')]
    public function demoteTasks(
        TaskLists $taskList,
        TasksRepository $tasksRepository,
        EntityManagerInterface $entityManager
    ): Response {
        // Get the maximum order number of tasks not in the specified task list
        $maxOrder = $tasksRepository->getMaxOrderNotInList($taskList);

        // Update tasks in the specified task list to have an order greater than the max order
        if ($maxOrder !== null) {
            $tasks = $tasksRepository->findBy(['taskList' => $taskList]);
            foreach ($tasks as $task) {
                $task->setOrder($maxOrder + 1); // Set new order
                $maxOrder++; // Increment for the next task
            }

            // Persist changes
            $entityManager->flush();
            $this->addFlash('success', 'TaskList: ' . $taskList->getName() . ' Demoted');
        }

        return $this->redirectToRoute('app_tasks_system'); // Redirect back to the dashboard
    }

    #[Route('/{id}/promote', name: 'app_task_lists_promote')]
    public function promoteTasks(
        TaskLists $taskList,
        TasksRepository $tasksRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $order = 0;
        // Update tasks in the specified task list to have an order greater than the max order
        if ($order !== null) {
            $tasks = $tasksRepository->findBy(['taskList' => $taskList]);
            foreach ($tasks as $task) {
                $task->setOrder($order + 1); // Set new order
                $order++; // Increment for the next task
            }

            // Persist changes
            $entityManager->flush();
            $this->addFlash('success', 'TaskList: ' . $taskList->getName() . ' Promoted');
        }

        return $this->redirectToRoute('app_tasks_system'); // Redirect back to the dashboard
    }
}
