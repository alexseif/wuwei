<?php

namespace App\Controller\Finance;

use App\Form\WorklogFiltersType;
use App\Repository\WorkLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/finance')]
final class WorkLogController extends AbstractController
{
    private array $twigParts = [
        'entity_name' => 'work_log',
        'entity_title' => 'Work Log'
    ];

    #[Route('/worklog', name: 'app_finance_work_log')]
    public function worklog(Request $request, WorkLogRepository $workLogRepository): Response
    {
        // Create the form and handle the request
        $form = $this->createForm(WorklogFiltersType::class);
        $form->handleRequest($request);

        // Initialize the query result
        $workLogs = [];

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
        //     // Get the filter data as an array
            $filterData = $form->getData();

        //     // Use the repository to filter work logs based on the form data
            $workLogs = $workLogRepository->findByFilters($filterData);
        } else {
        //     // If no filters are applied, fetch all work logs
            $workLogs = $workLogRepository->findAll();
        }

        // Pass the data to the Twig template
        $this->twigParts['work_logs'] = $workLogs;
        $this->twigParts['filter_form'] = $form->createView();

        return $this->render('finance/work_log/index.html.twig', $this->twigParts);
    }

    #[Route('/tasks', name: 'app_finance_tasks')]
    public function tasks(): Response
    {
        return $this->render('finance/work_log/index.html.twig', []);
    }
}
