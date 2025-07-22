<?php

namespace App\Controller;

use App\Entity\ProductService;
use App\Form\ProductServiceType;
use App\Repository\ProductServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product/services')]
final class ProductServiceController extends AbstractController
{

    private array $twigParts = [
      'entity_name' => 'product_service',
      'route_prefix' => 'product_service',
      'entity_title' => 'Product',
    ];

    #[Route('/', name: 'app_product_service_index', methods: ['GET'])]
    public function index(ProductServiceRepository $productServiceRepository
    ): Response {
        $this->twigParts['product_services'] = $productServiceRepository->findAll(
        );
        return $this->render(
          'product_service/index.html.twig',
          $this->twigParts
        );
    }

    #[Route('/new', name: 'app_product_service_new', methods: ['GET', 'POST'])]
    public function new(
      Request $request,
      EntityManagerInterface $entityManager
    ): Response {
        $productService = new ProductService();
        $form = $this->createForm(ProductServiceType::class, $productService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($productService);
            $entityManager->flush();

            return $this->redirectToRoute(
              'app_product_service_index',
              [],
              Response::HTTP_SEE_OTHER
            );
        }
        $this->twigParts['form'] = $form->createView();
        $this->twigParts['product_service'] = $productService;
        return $this->render('product_service/new.html.twig', $this->twigParts);
    }

    #[Route('/{id}', name: 'app_product_service_show', methods: ['GET'])]
    public function show(ProductService $productService): Response
    {
        $this->twigParts['product_service'] = $productService;
        $this->twigParts['entity'] = $productService;
        return $this->render(
          'product_service/show.html.twig',
          $this->twigParts
        );
    }

    #[Route('/{id}/edit', name: 'app_product_service_edit', methods: [
      'GET',
      'POST',
    ])]
    public function edit(
      Request $request,
      ProductService $productService,
      EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(ProductServiceType::class, $productService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute(
              'app_product_service_index',
              [],
              Response::HTTP_SEE_OTHER
            );
        }
        $this->twigParts['form'] = $form->createView();
        $this->twigParts['product_service'] = $productService;
        $this->twigParts['entity'] = $productService;
        return $this->render(
          'product_service/edit.html.twig',
          $this->twigParts
        );
    }

    #[Route('/{id}', name: 'app_product_service_delete', methods: ['POST'])]
    public function delete(
      Request $request,
      ProductService $productService,
      EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid(
          'delete' . $productService->getId(),
          $request->request->get('_token')
        )) {
            $entityManager->remove($productService);
            $entityManager->flush();
        }

        return $this->redirectToRoute(
          'app_product_service_index',
          [],
          Response::HTTP_SEE_OTHER
        );
    }

}
