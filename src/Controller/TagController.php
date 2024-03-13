<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tag')]
class TagController extends AbstractController
{

    #[Route('/', name: 'app_tag_index', methods: ['GET'])]
    public function index(TagRepository $tagRepository): Response
    {
        return $this->render('tag/index.html.twig', [
          'tags' => $tagRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tag_new', methods: ['GET', 'POST'])]
    public function new(
      Request $request,
      EntityManagerInterface $entityManager
    ): Response {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tag);
            $entityManager->flush();

            return $this->redirectToRoute(
              'app_tag_index',
              [],
              Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('tag/new.html.twig', [
          'tag' => $tag,
          'form' => $form,
        ]);
    }

    #[Route('/autocomplete', name: 'app_tag_autocomplete')]
    public function autocomplete(
      Request $request,
      TagRepository $tagRepository
    ): JsonResponse {
        // Your logic to fetch tag suggestions based on user input
        // Example: Use a TagRepository to query the database

        $input = $request->query->get('search', ''); // Get the input parameter

        // Perform your logic to get tag suggestions based on $input
        $tags = $tagRepository->searchForTag($input);

        // Format the response as expected by select2
        $data = [];
        foreach ($tags as $tag) {
            $data[] = [
              'id' => $tag->getId(),
              'text' => $tag->getName(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/{id}', name: 'app_tag_show', methods: ['GET'])]
    public function show(Tag $tag): Response
    {
        return $this->render('tag/show.html.twig', [
          'tag' => $tag,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tag_edit', methods: ['GET', 'POST'])]
    public function edit(
      Request $request,
      Tag $tag,
      EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute(
              'app_tag_index',
              [],
              Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('task_list/edit.html.twig', [
          'tag' => $tag,
          'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tag_delete', methods: ['POST'])]
    public function delete(
      Request $request,
      Tag $tag,
      EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid(
          'delete' . $tag->getId(),
          $request->request->get('_token')
        )) {
            $entityManager->remove($tag);
            $entityManager->flush();
        }

        return $this->redirectToRoute(
          'app_tag_index',
          [],
          Response::HTTP_SEE_OTHER
        );
    }

}
