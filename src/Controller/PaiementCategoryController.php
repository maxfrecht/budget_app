<?php

namespace App\Controller;

use App\Entity\PaiementCategory;
use App\Form\PaiementCategoryType;
use App\Repository\PaiementCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/paiement/category')]
class PaiementCategoryController extends AbstractController
{
    #[Route('/', name: 'paiement_category_index', methods: ['GET'])]
    public function index(PaiementCategoryRepository $paiementCategoryRepository): Response
    {
        return $this->render('paiement_category/index.html.twig', [
            'paiement_categories' => $paiementCategoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'paiement_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $paiementCategory = new PaiementCategory();
        $form = $this->createForm(PaiementCategoryType::class, $paiementCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($paiementCategory);
            $entityManager->flush();

            return $this->redirectToRoute('paiement_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('paiement_category/new.html.twig', [
            'paiement_category' => $paiementCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'paiement_category_show', methods: ['GET'])]
    public function show(PaiementCategory $paiementCategory): Response
    {
        return $this->render('paiement_category/show.html.twig', [
            'paiement_category' => $paiementCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'paiement_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PaiementCategory $paiementCategory): Response
    {
        $form = $this->createForm(PaiementCategoryType::class, $paiementCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('paiement_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('paiement_category/edit.html.twig', [
            'paiement_category' => $paiementCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'paiement_category_delete', methods: ['POST'])]
    public function delete(Request $request, PaiementCategory $paiementCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paiementCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($paiementCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('paiement_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
