<?php

namespace App\Controller;

use App\Entity\AccountTransactions;
use App\Form\AccountTransactionsType;
use App\Repository\AccountsRepository;
use App\Repository\AccountTransactionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/transactions')]
final class TransactionsController extends AbstractController
{

    private array $twigParts = [
      'entity_name' => 'account_transactions',
      'route_prefix' => 'transactions',
      'entity_title' => 'Transactions',
    ];

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
        $this->twigParts['transactions'] = $pagination;
        return $this->render(
          'account_transactions/index.html.twig',
          $this->twigParts
        );
    }

    #[Route('/new', name: 'app_transactions_new', methods: ['GET', 'POST'])]
    public function new(
      Request $request,
      EntityManagerInterface $entityManager,
      AccountsRepository $accountsRepository
    ): Response {
        $accountTransaction = new AccountTransactions();
        $accountId = $request->get('account') ?? null;
        if ($accountId) {
            $account = $accountsRepository->find($accountId);
            $accountTransaction->setAccount($account);
        }
        $form = $this->createForm(
          AccountTransactionsType::class,
          $accountTransaction
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($accountTransaction);
            $entityManager->flush();
            $this->addFlash('success', 'Transaction created successfully');

            if ($accountId) {
                return $this->redirectToRoute(
                  'app_accounts_show',
                  ['id' => $accountId]
                );
            }
            return $this->redirectToRoute(
              'app_transactions_index',
              [],
              Response::HTTP_SEE_OTHER
            );
        }
        $this->twigParts['form'] = $form->createView();
        $this->twigParts['account_transaction'] = $accountTransaction;
        return $this->render(
          'account_transactions/new.html.twig',
          $this->twigParts
        );
    }

    #[Route('/{id}', name: 'app_transactions_show', methods: ['GET'])]
    public function show(AccountTransactions $accountTransaction): Response
    {
        $this->twigParts['account_transaction'] = $accountTransaction;
        $this->twigParts['entity'] = $accountTransaction;
        $this->twigParts['entity_title'] .= ': #' . $accountTransaction->getId(
          );
        return $this->render(
          'account_transactions/show.html.twig',
          $this->twigParts
        );
    }

    #[Route('/{id}/edit', name: 'app_transactions_edit', methods: [
      'GET',
      'POST',
    ])]
    public function edit(
      Request $request,
      AccountTransactions $accountTransaction,
      EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(
          AccountTransactionsType::class,
          $accountTransaction
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute(
              'app_transactions_index',
              [],
              Response::HTTP_SEE_OTHER
            );
        }
        $this->twigParts['account_transaction'] = $accountTransaction;
        $this->twigParts['form'] = $form->createView();
        $this->twigParts['entity'] = $accountTransaction;

        return $this->render(
          'account_transactions/edit.html.twig',
          $this->twigParts
        );
    }

    #[Route('/{id}', name: 'app_transactions_delete', methods: ['POST'])]
    public function delete(
      Request $request,
      AccountTransactions $accountTransaction,
      EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid(
          'delete' . $accountTransaction->getId(),
          $request->getPayload()->getString('_token')
        )) {
            $entityManager->remove($accountTransaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute(
          'app_transactions_index',
          [],
          Response::HTTP_SEE_OTHER
        );
    }

}
