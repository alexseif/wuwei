<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Client;
use App\Entity\Tag;
use App\Entity\TagType;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account')]
class AccountController extends AbstractController
{

    #[Route('/', name: 'app_account_index', methods: ['GET'])]
    public function index(AccountRepository $accountRepository): Response
    {
        return $this->render('account/index.html.twig', [
          'accounts' => $accountRepository->findAll(),
        ]);
    }

    #[Route('/new/{clientId}', name: 'app_account_new', methods: [
      'GET',
      'POST',
    ], defaults: ['clientId' => null])]
    #[ParamConverter('client', class: 'App\Entity\Client', options: ['id' => 'clientId'])]
    public function new(
      Request $request,
      EntityManagerInterface $entityManager,
      Client $client = null
    ): Response {
        $account = new Account();
        if ($client) {
            $account->setClient($client);
        }

        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Account Tag
            $accountTagType = $entityManager->getRepository(TagType::class)
              ->findOneBy(
                ['name' => 'Account Tag']
              );
            $accountTag = new Tag();
            $accountTag->setTagType($accountTagType);
            $accountTag->setName($account->getName());
            $account->setAccountTag($accountTag);
            //TODO: updating an account tag

            $entityManager->persist($account);
            $entityManager->flush();

            return $this->redirectToRoute(
              'app_account_index',
              [],
              Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('account/new.html.twig', [
          'account' => $account,
          'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_account_show', methods: ['GET'])]
    public function show(Account $account): Response
    {
        return $this->render('account/show.html.twig', [
          'account' => $account,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_account_edit', methods: ['GET', 'POST'])]
    public function edit(
      Request $request,
      Account $account,
      EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute(
              'app_account_index',
              [],
              Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('account/edit.html.twig', [
          'account' => $account,
          'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_account_delete', methods: ['POST'])]
    public function delete(
      Request $request,
      Account $account,
      EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid(
          'delete' . $account->getId(),
          $request->request->get('_token')
        )) {
            $entityManager->remove($account);
            $entityManager->flush();
        }

        return $this->redirectToRoute(
          'app_account_index',
          [],
          Response::HTTP_SEE_OTHER
        );
    }

}
