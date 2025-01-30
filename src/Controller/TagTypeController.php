<?php

namespace App\Controller;

use App\Entity\TagType;
use App\Form\TagTypeType;
use App\Repository\TagRepository;
use App\Repository\TagTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tag_type')]
class TagTypeController extends AbstractController
{

    #[Route('/', name: 'app_tag_type_index', methods: ['GET'])]
    public function index(TagTypeRepository $tagTypeRepository): Response
    {
        return $this->render('tag_type/index.html.twig', [
          'tag_types' => $tagTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tag_type_new', methods: ['GET', 'POST'])]
    public function new(
      Request $request,
      EntityManagerInterface $entityManager
    ): Response {
        $tagType = new TagType();
        $form = $this->createForm(TagTypeType::class, $tagType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tagType);
            $entityManager->flush();

            return $this->redirectToRoute(
              'app_tag_type_index',
              [],
              Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('tag_type/new.html.twig', [
          'tag_type' => $tagType,
          'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tag_type_show', methods: ['GET'])]
    public function show(
      TagType $tagType,
      TagRepository $tagRepository
    ): Response {
        return $this->render('tag_type/show.html.twig', [
          'tag_type' => $tagType,
          'tags' => $tagRepository->findBy(['tagType' => $tagType]),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tag_type_edit', methods: ['GET', 'POST'])]
    public function edit(
      Request $request,
      TagType $tagType,
      EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(TagTypeType::class, $tagType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute(
              'app_tag_type_index',
              [],
              Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('tag_type/edit.html.twig', [
          'tag_type' => $tagType,
          'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tag_type_delete', methods: ['POST'])]
    public function delete(
      Request $request,
      TagType $tagType,
      EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid(
          'delete' . $tagType->getId(),
          $request->request->get('_token')
        )) {
            $entityManager->remove($tagType);
            $entityManager->flush();
        }

        return $this->redirectToRoute(
          'app_tag_type_index',
          [],
          Response::HTTP_SEE_OTHER
        );
    }

}
