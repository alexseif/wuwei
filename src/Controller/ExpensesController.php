<?php

namespace App\Controller;

use App\Entity\Expenses;
use App\Form\ExpensesType;
use App\Repository\ExpensesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/expenses')]
final class ExpensesController extends AbstractController
{
    private array $twigParts = [
        'entity_name' => 'expenses',
        'entity_title' => 'Expense',
    ];

    #[Route(name: 'app_expenses_index', methods: ['GET'])]
    public function index(ExpensesRepository $expensesRepository): Response
    {
        $this->twigParts['expenses'] = $expensesRepository->findAll();

        return $this->render('expenses/index.html.twig', $this->twigParts);
    }

    #[Route('/new', name: 'app_expenses_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entity = new Expenses();
        $form = $this->createForm(ExpensesType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entity);
            $entityManager->flush();

            $this->addFlash('success', 'Expense has been successfully created.');

            return $this->redirectToRoute('app_expenses_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->twigParts['expense'] = $entity;
        $this->twigParts['entity'] = $entity;
        $this->twigParts['form'] = $form;

        return $this->render('expenses/new.html.twig', $this->twigParts);
    }

    #[Route('/{id}', name: 'app_expenses_show', methods: ['GET'])]
    public function show(Expenses $entity): Response
    {
        $this->twigParts['entity'] = $entity;
        $this->twigParts['expense'] = $entity;

        return $this->render('expenses/show.html.twig', $this->twigParts);
    }

    #[Route('/{id}/edit', name: 'app_expenses_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Expenses $entity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExpensesType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Expense has been successfully updated.');

            return $this->redirectToRoute('app_expenses_index', [], Response::HTTP_SEE_OTHER);
        }
        $this->twigParts['expense'] = $entity;
        $this->twigParts['entity'] = $entity;
        $this->twigParts['form'] = $form;

        return $this->render('expenses/edit.html.twig', $this->twigParts);
    }

    #[Route('/{id}', name: 'app_expenses_delete', methods: ['POST'])]
    public function delete(Request $request, Expenses $entity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entity->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($entity);
            $entityManager->flush();
            $this->addFlash('success', 'Expense has been successfully deleted.');
        }

        return $this->redirectToRoute('app_expenses_index', [], Response::HTTP_SEE_OTHER);
    }
}
