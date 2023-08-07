<?php

namespace App\Controller;

use App\Repository\DailyRepository;
use App\Repository\ItemListRepository;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function home(
      ItemRepository $itemRepository,
      ItemListRepository $itemListRepository,
      DailyRepository $dailyRepository
    ): Response {
        $itemLists = $itemListRepository->findAllWithItems();
        $lastDay = $dailyRepository->getLastDaily();
        return $this->render(
          'dashboard/dashboard.html.twig',
          [
            'itemLists' => $itemLists,
            'lastDay' => $lastDay,
          ]
        );
    }


}
