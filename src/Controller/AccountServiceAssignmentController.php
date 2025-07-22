<?php

namespace App\Controller;

use App\Entity\AccountServiceAssignment;
use App\Form\AccountServiceAssignmentType;
use App\Repository\AccountServiceAssignmentRepository;
use App\Service\RenewalService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/account/service/assignment')]
final class AccountServiceAssignmentController extends AbstractController
{
    private array $twigParts = [
        'entity_name' => 'account_service_assignment',
        'route_prefix' => 'account_service_assignment',
        'entity_title' => 'Service Assignment',
    ];

    #[Route('/', name: 'app_account_service_assignment_index', methods: ['GET'])]
    public function index(AccountServiceAssignmentRepository $repository, RenewalService $renewalService): Response
    {
        $this->twigParts['assignments'] = $repository->findAll();
        $this->twigParts['upcoming_renewals'] = $renewalService->getUpcomingRenewals();
        $this->twigParts['overdue_renewals'] = $renewalService->getOverdueRenewals();

        return $this->render('account_service_assignment/index.html.twig', $this->twigParts);
    }

    #[Route('/new', name: 'app_account_service_assignment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $assignment = new AccountServiceAssignment();
        $form = $this->createForm(AccountServiceAssignmentType::class, $assignment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set default values if not provided
            if ($assignment->isComplete() === null) {
                $assignment->setIsComplete(false);
            }

            if ($assignment->getNote() === null) {
                $assignment->setNote('');
            }

            $entityManager->persist($assignment);
            $entityManager->flush();

            return $this->redirectToRoute('app_account_service_assignment_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->twigParts['form'] = $form->createView();
        $this->twigParts['assignment'] = $assignment;

        return $this->render('account_service_assignment/new.html.twig', $this->twigParts);
    }

    #[Route('/{id}', name: 'app_account_service_assignment_show', methods: ['GET'])]
    public function show(AccountServiceAssignment $assignment, RenewalService $renewalService): Response
    {
        $this->twigParts['assignment'] = $assignment;
        $this->twigParts['entity'] = $assignment;
        $this->twigParts['next_renewal'] = $renewalService->getNextRenewalDate($assignment);
        $this->twigParts['is_overdue'] = $renewalService->isOverdue($assignment);
        $this->twigParts['upcoming_dates'] = $renewalService->getUpcomingRenewalDates($assignment, 5);

        return $this->render('account_service_assignment/show.html.twig', $this->twigParts);
    }

    #[Route('/{id}/edit', name: 'app_account_service_assignment_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        AccountServiceAssignment $assignment,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(AccountServiceAssignmentType::class, $assignment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_account_service_assignment_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->twigParts['form'] = $form->createView();
        $this->twigParts['assignment'] = $assignment;
        $this->twigParts['entity'] = $assignment;

        return $this->render('account_service_assignment/edit.html.twig', $this->twigParts);
    }

    #[Route('/{id}', name: 'app_account_service_assignment_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        AccountServiceAssignment $assignment,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $assignment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($assignment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_account_service_assignment_index', [], Response::HTTP_SEE_OTHER);
    }
}
