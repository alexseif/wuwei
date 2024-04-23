<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\GoalRepository;
use App\Repository\ItemListRepository;
use App\Repository\ItemRepository;
use App\Repository\TagTypeRepository;
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
      TimeSystemService $timeSystemService,
      GoalRepository $goalRepository,
      TagTypeRepository $tagTypeRepository
    ): Response {
        $current = $timeSystemService->getCurrent();
        return $this->render(
          'dashboard/dashboard.html.twig',
          [
            'sections' => $tagTypeRepository->findAll(),
            'itemLists' => $itemListRepository->findAllWithItems(),
            'goals' => $goalRepository->findAll(),
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
