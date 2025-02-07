<?php

namespace App\Controller;

use App\Entity\Requirements;
use App\Form\RequirementsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/requirements')]
final class RequirementsController extends AbstractController
{
    private array $twigParts = [
        'entity_name' => 'requirements',
        'entity_title' => 'Requirement'
    ];

    #[Route(name: 'app_requirements_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $requirements = $entityManager
            ->getRepository(Requirements::class)
            ->findAll();


        return $this->render(
            'requirements/index.html.twig',
            array_merge(
                [
                    'requirements' => $requirements,
                ],
                $this->twigParts
            )
        );
    }

    #[Route('/new', name: 'app_requirements_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $requirement = new Requirements();
        $form = $this->createForm(RequirementsType::class, $requirement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($requirement);
            $entityManager->flush();

            return $this->redirectToRoute('app_requirements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('requirements/new.html.twig', array_merge([
            'entity' => $requirement,
            'form' => $form,
        ], $this->twigParts));
    }

    #[Route('/{id}', name: 'app_requirements_show', methods: ['GET'])]
    public function show(Requirements $requirement): Response
    {
        return $this->render('requirements/show.html.twig', array_merge([
            'requirement' => $requirement,
        ], $this->twigParts));
    }

    #[Route('/{id}/edit', name: 'app_requirements_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Requirements $requirement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RequirementsType::class, $requirement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_requirements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('requirements/edit.html.twig', array_merge([
            'entity' => $requirement,
            'form' => $form,
        ], $this->twigParts));
    }

    #[Route('/{id}', name: 'app_requirements_delete', methods: ['POST'])]
    public function delete(Request $request, Requirements $requirement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $requirement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($requirement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_requirements_index', [], Response::HTTP_SEE_OTHER);
    }
}
