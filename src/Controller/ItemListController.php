<?php

namespace App\Controller;

use App\Entity\ItemList;
use App\Form\ItemListType;
use App\Repository\ItemListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/itemlist')]
class ItemListController extends AbstractController
{
    #[Route('/', name: 'app_item_list_index', methods: ['GET'])]
    public function index(ItemListRepository $itemListRepository): Response
    {
        return $this->render('item_list/index.html.twig', [
            'item_lists' => $itemListRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_item_list_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $itemList = new ItemList();
        $form = $this->createForm(ItemListType::class, $itemList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($itemList);
            $entityManager->flush();

            return $this->redirectToRoute('app_item_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('item_list/new.html.twig', [
            'item_list' => $itemList,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_item_list_show', methods: ['GET'])]
    public function show(ItemList $itemList): Response
    {
        return $this->render('item_list/show.html.twig', [
            'item_list' => $itemList,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_item_list_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ItemList $itemList, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ItemListType::class, $itemList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_item_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('item_list/edit.html.twig', [
            'item_list' => $itemList,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_item_list_delete', methods: ['POST'])]
    public function delete(Request $request, ItemList $itemList, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$itemList->getId(), $request->request->get('_token'))) {
            $entityManager->remove($itemList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_item_list_index', [], Response::HTTP_SEE_OTHER);
    }
}
