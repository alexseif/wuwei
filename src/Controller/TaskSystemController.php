<?php

namespace App\Controller;

use App\Repository\TasksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/task/system')]
class TaskSystemController extends AbstractController
{
    #[Route('/', name: 'app_tasks_system')]
    public function index(TasksRepository $tasksRepository): Response
    {
        $completedTasks = $tasksRepository->getCompletedToday();

        $totalDuration = array_reduce($completedTasks, fn($carry, $task) => $carry + $task->getDuration(), 0);

        $tasksCreatedToday = $tasksRepository->getCreatedToday();
        $focusTasks = $tasksRepository->getFocusDayTasks();
        $tasksCompletedToday = $tasksRepository->getCompletedToday();

        return $this->render('task_system/index.html.twig', [
            'totalDuration' => $totalDuration,
            'focusTasks' => $focusTasks,
            'tasksCreatedToday' => $tasksCreatedToday,
            'tasksCompletedToday' => $tasksCompletedToday
        ]);
    }
}
