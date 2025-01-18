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
        $totalIncome = $accountTransactionsRepository->getTotalIncomeForCurrentMonth() * -1;
        $totalCostOfLife = $costOfLifeRepository->getTotalCostOfLifeForCurrentMonth() / 100;

        // Calculate the hourly rate
        $weeksPerMonth = 4.33; // Average number of weeks in a month
        $hoursPerWeek = 4 * 5; // 4 hours per day, 5 days per week
        $totalHoursPerMonth = $weeksPerMonth * $hoursPerWeek;
        $hourlyRate = $totalCostOfLife / $totalHoursPerMonth;

        $transactions = $accountTransactionsRepository->getTransactionsForCurrentMonth();

        return $this->render('finance/index.html.twig', [
            'totalIncome' => $totalIncome,
            'totalCostOfLife' => $totalCostOfLife,
            'hourlyRate' => $hourlyRate,
            'transactions' => $transactions,
        ]);
    }
}
