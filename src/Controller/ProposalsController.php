<?php

namespace App\Controller;

use App\Entity\Proposals;
use App\Form\ProposalsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/proposals')]
final class ProposalsController extends AbstractController
{
    private array $twigParts = [
        'entity_name' => 'proposals',
        'entity_title' => 'Proposal'
    ];

    #[Route(name: 'app_proposals_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $proposals = $entityManager
            ->getRepository(Proposals::class)
            ->findAll();

        $this->twigParts['proposals'] = $proposals;

        return $this->render('proposals/index.html.twig', $this->twigParts);
    }

    #[Route('/new', name: 'app_proposals_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $proposal = new Proposals();
        $form = $this->createForm(ProposalsType::class, $proposal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($proposal);
            $entityManager->flush();

            return $this->redirectToRoute('app_proposals_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->twigParts['proposal'] = $proposal;
        $this->twigParts['entity'] = $proposal;
        $this->twigParts['form'] = $form;
        return $this->render('proposals/new.html.twig', $this->twigParts);
    }

    #[Route('/{id}', name: 'app_proposals_show', methods: ['GET'])]
    public function show(Proposals $proposal): Response
    {
        $this->twigParts['proposal'] = $proposal;
        $this->twigParts['entity'] = $proposal;
        
        return $this->render('proposals/show.html.twig', $this->twigParts);
    }

    #[Route('/{id}/edit', name: 'app_proposals_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Proposals $proposal, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProposalsType::class, $proposal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_proposals_index', [], Response::HTTP_SEE_OTHER);
        }
        $this->twigParts['proposal'] = $proposal;
        $this->twigParts['entity'] = $proposal;
        $this->twigParts['form'] = $form;
        return $this->render('proposals/edit.html.twig', $this->twigParts);
    }

    #[Route('/{id}', name: 'app_proposals_delete', methods: ['POST'])]
    public function delete(Request $request, Proposals $proposal, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $proposal->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($proposal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_proposals_index', [], Response::HTTP_SEE_OTHER);
    }
}
