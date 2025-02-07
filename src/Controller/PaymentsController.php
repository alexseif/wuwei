<?php

namespace App\Controller;

use App\Entity\Payments;
use App\Form\PaymentsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/payments')]
final class PaymentsController extends AbstractController
{
    private array $twigParts = [
        'entity_name' => 'payments',
        'entity_title' => 'Payment'
    ];

    #[Route(name: 'app_payments_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $payments = $entityManager
            ->getRepository(Payments::class)
            ->findAll();

        $this->twigParts['payments'] = $payments;
        return $this->render('payments/index.html.twig', $this->twigParts);
    }

    #[Route('/new', name: 'app_payments_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $payment = new Payments();
        $form = $this->createForm(PaymentsType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($payment);
            $entityManager->flush();

            return $this->redirectToRoute('app_payments_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->twigParts['payment'] = $payment;
        $this->twigParts['entity'] = $payment;
        $this->twigParts['form'] = $form;
        return $this->render('payments/new.html.twig', $this->twigParts);
    }

    #[Route('/{id}', name: 'app_payments_show', methods: ['GET'])]
    public function show(Payments $payment): Response
    {
        $this->twigParts['payment'] = $payment;
        $this->twigParts['entity'] = $payment;
        return $this->render('payments/show.html.twig', $this->twigParts);
    }

    #[Route('/{id}/edit', name: 'app_payments_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Payments $payment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaymentsType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_payments_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->twigParts['payment'] = $payment;
        $this->twigParts['entity'] = $payment;
        $this->twigParts['form'] = $form;
        return $this->render('payments/edit.html.twig', $this->twigParts);
    }

    #[Route('/{id}', name: 'app_payments_delete', methods: ['POST'])]
    public function delete(Request $request, Payments $payment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $payment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($payment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_payments_index', [], Response::HTTP_SEE_OTHER);
    }
}
