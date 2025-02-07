<?php

namespace App\Controller;

use App\Entity\Transactions;
use App\Form\TransactionsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/transactions')]
final class TransactionsController extends AbstractController
{
    #[Route(name: 'app_transactions_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $transactions = $entityManager
            ->getRepository(Transactions::class)
            ->findAll();

        return $this->render('transactions/index.html.twig', [
            'transactions' => $transactions,
        ]);
    }

    #[Route('/new', name: 'app_transactions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $transaction = new Transactions();
        $form = $this->createForm(TransactionsType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($transaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_transactions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transactions/new.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transactions_show', methods: ['GET'])]
    public function show(Transactions $transaction): Response
    {
        return $this->render('transactions/show.html.twig', [
            'transaction' => $transaction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_transactions_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transactions $transaction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TransactionsType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_transactions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transactions/edit.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transactions_delete', methods: ['POST'])]
    public function delete(Request $request, Transactions $transaction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transaction->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($transaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_transactions_index', [], Response::HTTP_SEE_OTHER);
    }
}
