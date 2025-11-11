<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ComandCenterController extends AbstractController
{
    #[Route('/comand/center', name: 'app_comand_center')]
    public function index(): Response
    {
        return $this->render('comand_center/index.html.twig', []);
    }
}
