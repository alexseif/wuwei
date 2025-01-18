<?php

namespace App\Controller;

use App\Repository\AccountTransactionsRepository;
use App\Repository\CostOfLifeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/finance')]
class FinanceController extends AbstractController
{
    #[Route('/', name: 'app_finance')]
    public function index(AccountTransactionsRepository $accountTransactionsRepository, CostOfLifeRepository $costOfLifeRepository): Response
    {
        $totalIncome = $accountTransactionsRepository->getTotalIncomeForCurrentMonth();
        $totalCostOfLife = $costOfLifeRepository->getTotalCostOfLifeForCurrentMonth()/100;

        return $this->render('finance/index.html.twig', [
            'totalIncome' => $totalIncome,
            'totalCostOfLife' => $totalCostOfLife,
        ]);
    }
}
