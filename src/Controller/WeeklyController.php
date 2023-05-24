<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/weekly', name: 'app_weekly_')]
class WeeklyController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('weekly/index.html.twig', [
          'controller_name' => 'WeeklyController',
        ]);
    }

    #[Route('/show', name: 'show')]
    public function showDaily(): Response
    {
        $weeklyContent = file_get_contents(__DIR__ . '/../../docs/weekly.md');
        return $this->render('weekly/show.html.twig', [
          'schedule' => $weeklyContent,
        ]);
    }

}
