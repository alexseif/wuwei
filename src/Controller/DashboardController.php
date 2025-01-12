<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\GoalRepository;
use App\Repository\ItemListRepository;
use App\Repository\ItemRepository;
use App\Repository\TagTypeRepository;
use App\Repository\TaskDurationPerDayRepository;
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
    DurationReportService $durationReportService
  ): Response {
    $reportItems = ['today', 'yesterday', 'week', 'month', 'quarter', 'total'];
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
    // return new RedirectResponse($this->generateUrl('app_time_tracking'));
    $current = $timeSystemService->getCurrent();

    $habitBuilder = [
      'start' => new \DateTime('2025-01-12'),
      'days' => 66,
      'milestone' => 21,
      'habits' => [
        '6 - 8 am Sunrise' => [
          'Qi Gong / Push ups / Squat',
          'Breakfast'
        ],
        '08 - 10 am Creativity' => [
          'Creativity work',
        ],
        '10 - 12 pm Courage' => [
          'Courage work',
        ],
        '12 - 14 pm Guidance' => [
          'Guidance work',
        ],
        '14 - 16 pm Communication' => [
          'Communication',

        ],
        '16 - 18 pm New Beginnings' => [
          'Meditate / Walk',
          'Sing',
        ],
        '18 - 20 pm Sunset' => [
          'Review'
        ],
      ]
    ];

    return $this->render(
      'dashboard/dashboard.html.twig',
      [
        'sections' => $tagTypeRepository->findAll(),
        'itemLists' => $itemListRepository->findAllWithItems(),
        'goals' => $goalRepository->findAll(),
        'widgets' => $durationWidgets,
        'habit_builder' => $habitBuilder
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
