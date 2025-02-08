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
  private array $twigParts = [
    'entity_name' => 'tag',
    'entity_title' => 'Tag'
  ];

  #[Route('/', name: 'app_tag_index', methods: ['GET'])]
  public function index(TagRepository $tagRepository): Response
  {
    $this->twigParts['tags'] = $tagRepository->findAll();
    return $this->render('tag/index.html.twig', $this->twigParts);
  }

  #[Route('/new/{tagType}', name: 'app_tag_new', methods: ['GET', 'POST'])]
  public function new(
    Request $request,
    EntityManagerInterface $entityManager,
    \App\Entity\TagType $tagType = null
  ): Response {
    $tag = new Tag();
    if ($tagType) {
      $tag->setTagType($tagType);
    }
    $form = $this->createForm(TagType::class, $tag);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($tag);
      $entityManager->flush();

      $response = $this->redirectToRoute(
        'app_tag_index',
        [],
        Response::HTTP_SEE_OTHER
      );
      if ($tagType) {
        $response = $this->redirectToRoute(
          'app_tag_type_show',
          ['id' => $tagType->getId()],
          Response::HTTP_SEE_OTHER
        );
      }
      return $response;
    }
    $this->twigParts['tag'] = $tag;
    $this->twigParts['entity'] = $tag;
    $this->twigParts['form'] = $form;

    return $this->render('tag/new.html.twig', $this->twigParts);
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
    $this->twigParts['tag'] = $tag;
    $this->twigParts['entity'] = $tag;
    return $this->render('tag/show.html.twig', $this->twigParts);
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
    $this->twigParts['tag'] = $tag;
    $this->twigParts['entity'] = $tag;
    $this->twigParts['form'] = $form;
    return $this->render('tag/edit.html.twig', $this->twigParts);
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
