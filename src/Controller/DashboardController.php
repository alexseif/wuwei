<?php

namespace App\Controller;

use App\Repository\DailyRepository;
use App\Repository\ItemListRepository;
use App\Repository\ItemRepository;
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
      TimeSystemService $timeSystemService
    ): Response {
        $current = $timeSystemService->getCurrent();
        return $this->render(
          'dashboard/dashboard.html.twig',
          [
            'itemLists' => $itemListRepository->findAllWithItems(),
            'daily' => $dailyRepository->getLastDaily(),
          ]
        );
    }


}
