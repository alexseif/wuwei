<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/task/system', name: 'app_task_system')]
class TaskSystemController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('task_system/index.html.twig', [
            
        ]);
    }
}
