<?php

namespace App\Service;

use App\Entity\Currency;
use App\Repository\CurrencyRepository;

class CurrencyService
{
    public function __construct(private readonly CurrencyRepository $currencyRepository) {}

    public function getUsd(): Currency
    {
        return $this->getByCurrencyCode('USD');
    }
    public function getEur(): Currency
    {
        return $this->getByCurrencyCode('EUR');
    }
    public function getByCurrencyCode($currencyCode): Currency
    {
        $currency = $this->currencyRepository->findOneBy(['code' => $currencyCode]);
        $currency->setEgp($currency->getEgp() / 100);
        return $currency;
    }
}
