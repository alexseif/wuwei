<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WASController extends AbstractController
{

    #[Route('/was', name: 'app_was')]
    public function index(): Response
    {
        return $this->render('was/index.html.twig', [
          'controller_name' => 'WASController',
        ]);
    }

}
