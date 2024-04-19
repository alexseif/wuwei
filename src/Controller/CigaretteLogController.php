<?php

namespace App\Controller;

use App\Entity\CigaretteLog;
use App\Repository\CigaretteLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cigarette_log')]
class CigaretteLogController extends AbstractController
{

    #[Route('/', name: 'app_cigarette_log_index', methods: ['GET'])]
    public function index(CigaretteLogRepository $cigaretteLogRepository
    ): Response {
        return $this->render('cigarette_log/index.html.twig', [
          'cigarette_logs' => $cigaretteLogRepository->findBy([],
            ['createdAt' => 'DESC']),
          'cigarette_counts' => $cigaretteLogRepository->countByDay(),
        ]);
    }

    #[Route('/new', name: 'app_cigarette_log_new', methods: ['GET', 'POST'])]
    public function new(
      Request $request,
      EntityManagerInterface $entityManager
    ): Response {
        $cigaretteLog = new CigaretteLog();

        $entityManager->persist($cigaretteLog);
        $entityManager->flush();
        //Fetch the request referrer
        $referer = $request->headers->get('referer');
        //Get the symfony path for the referrer
        $path = parse_url($referer, PHP_URL_PATH);
        //Redirect to the referrer
        return $this->redirect($path, Response::HTTP_SEE_OTHER);
        //        return $this->redirectToRoute('app_cigarette_log_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_cigarette_log_show', methods: ['GET'])]
    public function show(CigaretteLog $cigaretteLog): Response
    {
        return $this->render('cigarette_log/show.html.twig', [
          'cigarette_log' => $cigaretteLog,
        ]);
    }

    #[Route('/{id}', name: 'app_cigarette_log_delete', methods: ['POST'])]
    public function delete(
      Request $request,
      CigaretteLog $cigaretteLog,
      EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid(
          'delete' . $cigaretteLog->getId(),
          $request->request->get('_token')
        )) {
            $entityManager->remove($cigaretteLog);
            $entityManager->flush();
        }

        return $this->redirectToRoute(
          'app_cigarette_log_index',
          [],
          Response::HTTP_SEE_OTHER
        );
    }

}
