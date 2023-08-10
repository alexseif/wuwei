<?php

namespace App\Controller;

use App\Entity\Daily;
use App\Entity\Item;
use App\Form\DailyType;
use App\Form\ItemType;
use App\Repository\DailyRepository;
use App\Repository\ItemListRepository;
use App\Repository\ItemRepository;
use App\Service\DailyService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/daily')]
class DailyController extends AbstractController
{

    #[Route('/', name: 'app_daily_index', methods: ['GET'])]
    public function index(DailyRepository $dailyRepository): Response
    {
        return $this->render('daily/index.html.twig', [
          'dailies' => $dailyRepository->findAll(),
        ]);
    }

    #[Route('/create_daily', name: 'app_create_daily')]
    public function createDaily(DailyService $dailyService): Response
    {
        $daily = $dailyService->createDailyWithMigration();

        return $this->redirectToRoute(
          'app_daily_show',
          ['id' => $daily->getId()]
        );
    }

    #[Route('/
    choose_items/{id}', name: 'app_choose_items')]
    public function chooseItems(
      Request $request,
      Daily $daily,
      DailyService $dailyService
    ): Response {
        // Get the list of items from previous daily entities (You can customize this logic as needed)
        $previousItems = $dailyService->getItemsNotInDaily($daily);
        return $this->render('daily/choose_items.html.twig', [
          'daily' => $daily,
          'previousItems' => $previousItems,
        ]);
    }

    #[Route('/migrate_items/{id}', name: 'app_migrate_items', methods: ['POST'])]
    public function migrateItems(
      Request $request,
      Daily $daily,
      DailyService $dailyService
    ): Response {
        // Retrieve the selected item IDs from the form submission
        $selectedItemIds = (array)$request->request->get('items', []);

        $dailyService->migrateItems($daily, $selectedItemIds);

        return $this->redirectToRoute(
          'app_daily_show',
          ['id' => $daily->getId()]
        );
    }


    #[Route('/new', name: 'app_daily_new', methods: ['GET', 'POST'])]
    public function new(
      Request $request,
      EntityManagerInterface $entityManager
    ): Response {
        $daily = new Daily();
        $form = $this->createForm(DailyType::class, $daily);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($daily);
            $entityManager->flush();

            return $this->redirectToRoute(
              'app_daily_show',
              ['id' => $daily->getId()],
              // Pass the newly created daily entity's ID as a parameter
              Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('daily/new.html.twig', [
          'daily' => $daily,
          'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_daily_show', methods: ['GET', 'POST'])]
    public function show(
      Request $request,
      Daily $daily,
      ItemListRepository $itemListRepository,
      EntityManagerInterface $entityManager
    ): Response {
        $item = new Item();
        //Item has to be in a list
        $dailiesList = $itemListRepository->findOneBy(['name' => 'Dailies']);
        $item->setList($dailiesList);
        $form = $this->createForm(ItemType::class, $item);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $daily->addItem($item);

            $entityManager->persist($item);
            $entityManager->flush();

            // Redirect back to the daily page or wherever you prefer
            return $this->redirectToRoute(
              'app_daily_show',
              ['id' => $daily->getId()]
            );
        }
        return $this->render('daily/show.html.twig', [
          'daily' => $daily,
          'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_daily_edit', methods: ['GET', 'POST'])]
    public function edit(
      Request $request,
      Daily $daily,
      EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(DailyType::class, $daily);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute(
              'app_daily_index',
              [],
              Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('daily/edit.html.twig', [
          'daily' => $daily,
          'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_daily_delete', methods: ['POST'])]
    public function delete(
      Request $request,
      Daily $daily,
      EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid(
          'delete' . $daily->getId(),
          $request->request->get('_token')
        )) {
            $entityManager->remove($daily);
            $entityManager->flush();
        }

        return $this->redirectToRoute(
          'app_daily_index',
          [],
          Response::HTTP_SEE_OTHER
        );
    }

}
