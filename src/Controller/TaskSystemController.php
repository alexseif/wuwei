<?php

namespace App\Controller;

use App\Repository\TasksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/task/system')]
class TaskSystemController extends AbstractController
{
    #[Route('/', name: 'app_task_system')]
    public function index(TasksRepository $tasksRepository): Response
    {
        $today = new \DateTime('today');
        $completedTasks = $tasksRepository->findBy(['completed' => true, 'completedAt' => $today]);

        $totalDuration = array_reduce($completedTasks, function ($carry, $task) {
            return $carry + $task->getDuration();
        }, 0);

        $todayTasks = $tasksRepository->getCreatedToday();
        $tasks = $tasksRepository->getFocusDayTasks();

        return $this->render('task_system/index.html.twig', [
            'totalDuration' => $totalDuration,
            'tasks' => $tasks,
            'todayTasks' => $todayTasks
        ]);
    }
}
