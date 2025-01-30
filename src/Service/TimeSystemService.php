<?php

namespace App\Service;

use App\Repository\TimeSystemRepository;
use App\Utility\DateTime;
use Doctrine\ORM\EntityManagerInterface;

class TimeSystemService
{


    public function __construct(private readonly TimeSystemRepository $timeSystemRepository)
    {
    }

    public function getCurrent(): array
    {
        return $this->timeSystemRepository->getCurrent();
    }

}