<?php

namespace App\User\Controller;


use App\User\Form\ProfileType;
use App\User\Entity\Profile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/profile')]
class ProfileController extends AbstractController
{

  #[Route('/', name: 'app_profile_show', methods: ['GET'])]
  public function show(): Response
  {
    $profile = null;
    if (!$this->getUser()) {
      throw new AccessDeniedException(
        'You must be logged in to access this page.'
      );
    }

    $profile = $this->getUser()->getProfile();
    // Use the $profile object
    return $this->render('profile/show.html.twig', [
      'profile' => $profile,
    ]);
  }

  #[Route('/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
  public function edit(
    Request $request,
    EntityManagerInterface $entityManager
  ): Response {
    $profile = null;
    if (!$this->getUser()) {
      throw new AccessDeniedException(
        'You must be logged in to access this page.'
      );
    }

    $profile = $this->getUser()->getProfile();
    // Use the $profile object
    if (!$profile) {
      $profile = new Profile();
      $profile->setUser($this->getUser());
    }
    $form = $this->createForm(ProfileType::class, $profile);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      if (!$profile->getId()) {
        $entityManager->persist($profile);
      }
      $entityManager->flush();

      return $this->redirectToRoute(
        'app_profile_show',
        [],
        Response::HTTP_SEE_OTHER
      );
    }

    return $this->render('profile/edit.html.twig', [
      'profile' => $profile,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'app_profile_delete', methods: ['POST'])]
  public function delete(
    Request $request,
    Profile $profile,
    EntityManagerInterface $entityManager
  ): Response {
    if ($this->isCsrfTokenValid(
      'delete' . $profile->getId(),
      $request->request->get('_token')
    )) {
      $entityManager->remove($profile);
      $entityManager->flush();
    }

    return $this->redirectToRoute(
      'app_profile_show',
      [],
      Response::HTTP_SEE_OTHER
    );
  }
}
