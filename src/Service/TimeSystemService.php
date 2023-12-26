<?php

namespace App\Service;

use App\Repository\TimeSystemRepository;
use App\Utility\DateTime;
use Doctrine\ORM\EntityManagerInterface;

class TimeSystemService
{


    private TimeSystemRepository $timeSystemRepository;


    public function __construct(
      TimeSystemRepository $timeSystemRepository,
    ) {
        $this->timeSystemRepository = $timeSystemRepository;
    }

    public function getCurrent(): array
    {
        return $this->timeSystemRepository->getCurrent();
    }

}