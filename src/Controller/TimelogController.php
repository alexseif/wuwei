<?php

namespace App\Controller;

use App\Entity\Timelog;
use App\Form\TimelogType;
use App\Repository\TimelogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/timelog')]
final class TimelogController extends AbstractController
{
    private array $twigParts = [
        'entity_name' => 'timelog',
        'entity_title' => 'Timelog'
    ];


    #[Route(name: 'app_timelog_index', methods: ['GET'])]
    public function index(TimelogRepository $timelogRepository): Response
    {

        $this->twigParts['timelogs'] = $timelogRepository->findAll();
        return $this->render('timelog/index.html.twig', $this->twigParts);
    }

    #[Route('/new', name: 'app_timelog_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $timelog = new Timelog();
        $form = $this->createForm(TimelogType::class, $timelog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($timelog);
            $entityManager->flush();
            return $this->redirectToRoute('app_timelog_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->twigParts['timelog'] = $timelog;
        $this->twigParts['entity'] = $timelog;
        $this->twigParts['form'] = $form;

        return $this->render('timelog/new.html.twig', $this->twigParts);
    }

    #[Route('/{id}', name: 'app_timelog_show', methods: ['GET'])]
    public function show(Timelog $timelog): Response
    {
        $this->twigParts['entity'] = $timelog;
        $this->twigParts['timelog'] = $timelog;
        return $this->render('timelog/show.html.twig', $this->twigParts);
    }

    #[Route('/{id}/edit', name: 'app_timelog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Timelog $timelog, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TimelogType::class, $timelog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_timelog_index', [], Response::HTTP_SEE_OTHER);
        }
        $this->twigParts['timelog'] = $timelog;
        $this->twigParts['entity'] = $timelog;
        $this->twigParts['form'] = $form;

        return $this->render('timelog/edit.html.twig', $this->twigParts);
    }

    #[Route('/{id}', name: 'app_timelog_delete', methods: ['POST'])]
    public function delete(Request $request, Timelog $timelog, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $timelog->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($timelog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_timelog_index', [], Response::HTTP_SEE_OTHER);
    }
}
