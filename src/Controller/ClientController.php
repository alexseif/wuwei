<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/client')]
final class ClientController extends AbstractController
{

    private array $twigParts = [
        'entity_name' => 'client',
        'entity_title' => 'Client'
    ];

    #[Route(name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        $this->twigParts['clients'] = $clientRepository->findAll();
        return $this->render('client/index.html.twig', $this->twigParts);
    }

    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash('success', 'Client has been successfully created.');

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->twigParts['client'] = $client;
        $this->twigParts['entity'] = $client;
        $this->twigParts['form'] = $form;
        return $this->render('client/new.html.twig', $this->twigParts);
    }

    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        $this->twigParts['entity'] = $client;
        $this->twigParts['client'] = $client;
        return $this->render('client/show.html.twig', $this->twigParts);
    }

    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Client has been successfully updated.');

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }
        $this->twigParts['client'] = $client;
        $this->twigParts['entity'] = $client;
        $this->twigParts['form'] = $form;

        return $this->render('client/edit.html.twig', $this->twigParts);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $client->getId(), $request->getPayload()->getString('_token'))) {
            if (count($client->getAccounts()) > 0) {
                $this->addFlash('error', 'Client has accounts please deleted them first.');
                return $this->redirectToRoute('app_client_show', ['id' => $client->getId()], Response::HTTP_SEE_OTHER);
            }
            $entityManager->remove($client);
            $entityManager->flush();

            $this->addFlash('success', 'Client has been successfully deleted.');
        } else {
            $this->addFlash('error', 'Invalid CSRF token. Client could not be deleted.');
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
