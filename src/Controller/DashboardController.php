<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\DailyRepository;
use App\Repository\GoalRepository;
use App\Repository\ItemListRepository;
use App\Repository\ItemRepository;
use App\Repository\TaskRepository;
use App\Service\TimeSystemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{

    #[Route('/', name: 'app_dashboard')]
    public function home(
      ItemRepository $itemRepository,
      ItemListRepository $itemListRepository,
      DailyRepository $dailyRepository,
      TimeSystemService $timeSystemService,
      TaskRepository $taskRepository,
      GoalRepository $goalRepository
    ): Response {
        $current = $timeSystemService->getCurrent();
        $taskList = new Tag();
        $taskList->setName("1000 ToDo\'s");
        return $this->render(
          'dashboard/dashboard.html.twig',
          [
            'itemLists' => $itemListRepository->findAllWithItems(),
            'daily' => $dailyRepository->getLastDaily(),
            'taskList' => $taskList,
            'tasks' => $taskRepository->findAll(),
            'goals' => $goalRepository->findAll(),
          ]
        );
    }


}
