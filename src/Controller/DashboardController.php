<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\DaysRepository;
use App\Repository\GoalRepository;
use App\Repository\ItemListRepository;
use App\Repository\ItemRepository;
use App\Repository\TagTypeRepository;
use App\Repository\TaskDurationPerDayRepository;
use App\Repository\TasksRepository;
use App\Service\DurationReportService;
use App\Service\TimeSystemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{

  #[Route('/', name: 'app_dashboard')]
  public function home(
    ItemRepository $itemRepository,
    ItemListRepository $itemListRepository,
    TimeSystemService $timeSystemService,
    GoalRepository $goalRepository,
    TagTypeRepository $tagTypeRepository,
    TaskDurationPerDayRepository $taskDurationPerDayRepository,
    DurationReportService $durationReportService,
    DaysRepository $daysRepository,
    TasksRepository $tasksRepository
  ): Response {
    $reportItems = ['today','week', 'month', 'quarter', 'total'];
    $defaultWidget = [
      'title' => 'Time Tracking',
      'icon' => 'bi-clock',
      'color' => 'bg-primary',
      'value' => '0:00',
      'valueLastYear' => null,
    ];

    $durationWidgets = array_map(function ($item) use ($taskDurationPerDayRepository, $durationReportService, $defaultWidget) {
      $widget = $defaultWidget;
      $widget['title'] = ucwords(str_replace('hours', '', $item));
      $functionName = 'get' . ucwords($item);
      $functionNameLastYear = $functionName . 'LastYear';
      $itemValue = $taskDurationPerDayRepository->$functionName();
      $widget['value'] = $durationReportService->formatMinutesToHours($itemValue);

      if (!in_array($item, ['total', 'yesterday'])) {
        $itemValueLastYear = $taskDurationPerDayRepository->$functionNameLastYear();
        $widget['valueLastYear'] = $durationReportService->formatMinutesToHours($itemValueLastYear);
        $widget['percentage'] = $durationReportService->calculatePercentageDifference($itemValue, $itemValueLastYear);
        $color = $widget['percentage'] < 0 ? 'danger' : 'success';
        $widget['percentageColor'] = 'bg-' . $color;
        $widget['trend'] = $widget['percentage'] < 0 ? 'down' : 'up';
        $widget['trendIcon'] = $widget['percentage'] < 0 ? 'bi-arrow-down' : 'bi-arrow-up';
        $widget['trendColor'] = 'text-' . $color;
      }

      return $widget;
    }, $reportItems);

    $currentTimeSystem = $timeSystemService->getCurrent();
    $days = $daysRepository->findBy(['complete' => false], ['deadline' => 'ASC'], 3);
    $tasks = $tasksRepository->getFocusTasks();

    return $this->render(
      'dashboard/dashboard.html.twig',
      [
        'sections' => $tagTypeRepository->findAll(),
        'itemLists' => $itemListRepository->findAllWithItems(),
        'goals' => $goalRepository->findAll(),
        'widgets' => $durationWidgets,
        'currentTimeSystem' => $currentTimeSystem,
        'days' => $days,
        'tasks' => $tasks
      ]
    );
  }

  #[Route('/dev', name: 'app_dashboard_dev')]
  public function dev(
    ItemRepository $itemRepository,
    ItemListRepository $itemListRepository,
    TimeSystemService $timeSystemService,
    GoalRepository $goalRepository,
    TagTypeRepository $tagTypeRepository
  ): Response {
    $current = $timeSystemService->getCurrent();
    return $this->render(
      'dashboard/dev_dashboard.html.twig',
      [
        'sections' => $tagTypeRepository->findAll(),
        'itemLists' => $itemListRepository->findAllWithItems(),
        'goals' => $goalRepository->findAll(),
      ]
    );
  }
}
