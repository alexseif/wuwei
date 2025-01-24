<?php

namespace App\Controller;

use App\Entity\Days;
use App\Form\DaysType;
use App\Repository\DaysRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/days')]
final class DaysController extends AbstractController
{
    #[Route(name: 'app_days_index', methods: ['GET'])]
    public function index(Request $request, DaysRepository $daysRepository): Response
    {
        $complete = $request->query->get('complete') ?? false;
        return $this->render('days/index.html.twig', [
            'days' => $daysRepository->findBy(['complete' => $complete], ['deadline' => 'ASC']),
            'complete' => $complete
        ]);
    }

    #[Route('/new', name: 'app_days_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $day = new Days();
        $form = $this->createForm(DaysType::class, $day);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($day);
            $entityManager->flush();
            $this->addFlash('success', 'Day Created');

            return $this->redirectToRoute('app_days_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('days/new.html.twig', [
            'day' => $day,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_days_show', methods: ['GET'])]
    public function show(Days $day, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView('days/_show.html.twig', [
                'day' => $day,
            ]);
            return new JsonResponse([
                'content' => $content,
                'label' => 'Day: ' . $day->getName()
            ]);
        }
        return $this->render('days/show.html.twig', [
            'day' => $day,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_days_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Days $day, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DaysType::class, $day, [
            'action' => $this->generateUrl('app_days_edit', ['id' => $day->getId()])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Day Updated');

            return $this->redirectToRoute('app_days_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($request->isXmlHttpRequest()) {
            $content = $this->renderView('days/_form.html.twig', [
                'day' => $day,
                'form' => $form->createView(),
            ]);
            return new JsonResponse([
                'content' => $content,
                'label' => 'Edit Task'
            ]);
        }
        return $this->render('days/edit.html.twig', [
            'day' => $day,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_days_delete', methods: ['POST'])]
    public function delete(Request $request, Days $day, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $day->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($day);
            $entityManager->flush();
            $this->addFlash('success', 'Day Deleted');
        }

        return $this->redirectToRoute('app_days_index', [], Response::HTTP_SEE_OTHER);
    }
}
