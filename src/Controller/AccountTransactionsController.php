<?php

namespace App\Controller;

use App\Entity\AccountTransactions;
use App\Form\AccountTransactionsType;
use App\Repository\AccountTransactionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/transactions')]
final class AccountTransactionsController extends AbstractController
{
    #[Route(name: 'app_transactions_index', methods: ['GET'])]
    public function index(
        Request $request,
        AccountTransactionsRepository $accountTransactionsRepository,
        PaginatorInterface $paginator
    ): Response {
        $pagination = $paginator->paginate(
            $accountTransactionsRepository->getPaginatorQuery(),
            $request->query->getInt('page', 1)
        );
        return $this->render('account_transactions/index.html.twig', [
            'account_transactions' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_transactions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $accountTransaction = new AccountTransactions();
        $form = $this->createForm(AccountTransactionsType::class, $accountTransaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($accountTransaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_transactions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account_transactions/new.html.twig', [
            'account_transaction' => $accountTransaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transactions_show', methods: ['GET'])]
    public function show(AccountTransactions $accountTransaction): Response
    {
        return $this->render('account_transactions/show.html.twig', [
            'account_transaction' => $accountTransaction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_transactions_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AccountTransactions $accountTransaction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AccountTransactionsType::class, $accountTransaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_transactions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account_transactions/edit.html.twig', [
            'account_transaction' => $accountTransaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transactions_delete', methods: ['POST'])]
    public function delete(Request $request, AccountTransactions $accountTransaction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $accountTransaction->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($accountTransaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_transactions_index', [], Response::HTTP_SEE_OTHER);
    }
}
