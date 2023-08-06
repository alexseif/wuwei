<?php

namespace App\Controller;

use App\Repository\ItemListRepository;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function Home(
      ItemRepository $itemRepository,
      ItemListRepository $itemListRepository
    ): Response {
        $itemLists = $itemListRepository->findAllWithItems();
        return $this->render(
          'default/index.html.twig',
          ['itemLists' => $itemLists]
        );
    }


}
