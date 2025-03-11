<?php

namespace App\Service;

use App\Entity\Currency;
use App\Repository\CostOfLifeRepository;
use App\Repository\CurrencyRepository;

class CostService
{

    public function __construct(private readonly CostOfLifeRepository $costOfLifeRepository, private readonly CurrencyService $currencyService) {}
    public function getCosts()
    {

        $USD = $this->currencyService->getUsd();
        $EUR = $this->currencyService->getEur();
        $costOfLife = $this->costOfLifeRepository->findAll();
        $total = 0;

        foreach ($costOfLife as $cost) {
            $total += $cost->getValue();
        }
        $minimumHourly = 27 / $USD->getEGP();
        $total = $total / 100;
        $hourly = $total / (4 * 20);
        $hourly = ($minimumHourly > $hourly) ? $minimumHourly : $hourly;
        $daily = $hourly * 4;
        $weekly = $daily * 5;
        $costs = [
            'hourly' => [
                'EGP' => $hourly,
                'USD' => ($hourly * $USD->getEGP()),
                'EUR' => ($hourly * $EUR->getEGP())
            ],
            'daily' => [
                'EGP' => $daily,
                'USD' => ($daily * $USD->getEGP()),
                'EUR' => ($daily * $EUR->getEGP())
            ],
            'weekly' => [
                'EGP' => $weekly,
                'USD' => ($weekly * $USD->getEGP()),
                'EUR' => ($weekly * $EUR->getEGP())
            ],
            'monthly' => [
                'EGP' => $total,
                'USD' => ($total * $USD->getEGP()),
                'EUR' => ($total * $EUR->getEGP())
            ]
        ];
        return $costs;
    }
}
