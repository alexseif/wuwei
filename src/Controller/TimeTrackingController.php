<?php

namespace App\Controller;

use App\Repository\TaskDurationPerDayRepository;
use App\Service\DurationReportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/time-tracking')]
class TimeTrackingController extends AbstractController
{
    #[Route('/', name: 'app_time_tracking')]
    public function index(TaskDurationPerDayRepository $taskDurationPerDayRepository, DurationReportService $durationReportService): Response
    {
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

        return $this->render('time_tracking/index.html.twig', ['widgets' => $durationWidgets]);
    }
}
