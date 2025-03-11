<?php

namespace App\Controller;

use App\Entity\CostOfLife;
use App\Form\CostOfLifeType;
use App\Repository\CostOfLifeRepository;
use App\Service\CostService;
use App\Service\CurrencyService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cost-of-life')]
final class CostOfLifeController extends AbstractController
{
    #[Route(name: 'app_cost_of_life_index', methods: ['GET'])]
    public function index(CostOfLifeRepository $costOfLifeRepository, CostService $costService): Response
    {
        $costOfLife = $costOfLifeRepository->findAll();
        $costs = $costService->getCosts();
        return $this->render('cost_of_life/index.html.twig', [
            'cost_of_lives' => $costOfLife,
            'costs' => $costs
        ]);
    }

    #[Route('/new', name: 'app_cost_of_life_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $costOfLife = new CostOfLife();
        $form = $this->createForm(CostOfLifeType::class, $costOfLife);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($costOfLife);
            $entityManager->flush();

            return $this->redirectToRoute('app_cost_of_life_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cost_of_life/new.html.twig', [
            'cost_of_life' => $costOfLife,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cost_of_life_show', methods: ['GET'])]
    public function show(CostOfLife $costOfLife): Response
    {
        return $this->render('cost_of_life/show.html.twig', [
            'cost_of_life' => $costOfLife,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cost_of_life_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CostOfLife $costOfLife, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CostOfLifeType::class, $costOfLife);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cost_of_life_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cost_of_life/edit.html.twig', [
            'cost_of_life' => $costOfLife,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cost_of_life_delete', methods: ['POST'])]
    public function delete(Request $request, CostOfLife $costOfLife, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $costOfLife->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($costOfLife);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cost_of_life_index', [], Response::HTTP_SEE_OTHER);
    }
}
