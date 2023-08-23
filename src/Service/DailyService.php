<?php
// src/Service/DailyService.php

namespace App\Service;

use App\Entity\Daily;
use App\Repository\DailyRepository;
use App\Repository\ItemRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class DailyService
{

    private DailyRepository $dailyRepository;

    private ItemRepository $itemRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
      DailyRepository $dailyRepository,
      ItemRepository $itemRepository,
      EntityManagerInterface $entityManager
    ) {
        $this->dailyRepository = $dailyRepository;
        $this->itemRepository = $itemRepository;
        $this->entityManager = $entityManager;
    }

    public function createDailyWithMigration(): Daily
    {
        $date = new DateTime();
        $formattedDate = $date->format('Y-m-d');

        // Check if today's daily already exists
        $daily = $this->dailyRepository->findOneByName($formattedDate);

        if (!$daily) {
            // Create a new daily entity for today
            $daily = new Daily();
            $daily->setName($formattedDate);

            // Optionally, you can migrate items from the previous daily entity here
            // $previousDaily = $this->dailyRepository->getLastDaily();
            // $itemsToMigrate = $previousDaily ? $previousDaily->getItems() : [];
            // foreach ($itemsToMigrate as $item) {
            //     $daily->addItem($item);
            // }

            $this->entityManager->persist($daily);
            $this->entityManager->flush();
        }

        return $daily;
    }

    public function getItemsNotInDaily(Daily $daily)
    {
        return $this->itemRepository->findItemsNotInDaily($daily);
    }

    public function migrateItems(Daily $daily, array $selectedItemIds): void
    {
        // Retrieve and migrate the selected items to the new daily entity
        foreach ($selectedItemIds as $itemId) {
            $item = $this->itemRepository->find($itemId);
            if ($item) {
                $daily->addItem($item);
            }
        }

        // Persist the changes
        $this->entityManager->persist($daily);
        $this->entityManager->flush();
    }

}
