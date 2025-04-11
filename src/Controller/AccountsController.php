<?php

namespace App\Controller;

use App\Entity\Accounts;
use App\Form\AccountsType;
use App\Repository\AccountsRepository;
use App\Repository\AccountTransactionsRepository;
use App\Repository\TaskListsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accounts')]
final class AccountsController extends AbstractController
{
    #[Route(name: 'app_accounts_index', methods: ['GET'])]
    public function index(AccountsRepository $accountsRepository): Response
    {
        return $this->render('accounts/index.html.twig', [
            'accounts' => $accountsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_accounts_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $account = new Accounts();
        $form = $this->createForm(AccountsType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($account);
            $entityManager->flush();

            return $this->redirectToRoute('app_accounts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('accounts/new.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_accounts_show', methods: ['GET'])]
    public function show(Accounts $account, AccountTransactionsRepository $accountTransactionsRepository, PaginatorInterface $paginator, TaskListsRepository $taskListsRepository): Response
    {
        $taskLists = $taskListsRepository->findBy(['account' => $account]);
        $transactions  = $paginator->paginate($accountTransactionsRepository->getPaginatorQuery(['at.account' => $account]), 1);

        return $this->render('accounts/show.html.twig', [
            'account' => $account,
            'taskLists' => $taskLists,
            'transactions' => $transactions,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_accounts_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Accounts $account, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AccountsType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_accounts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('accounts/edit.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_accounts_delete', methods: ['POST'])]
    public function delete(Request $request, Accounts $account, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $account->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($account);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_accounts_index', [], Response::HTTP_SEE_OTHER);
    }
}
