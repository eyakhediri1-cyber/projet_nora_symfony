<?php

namespace App\Controller;

use App\Entity\TagEvenement;
use App\Form\TagEvenementType;
use App\Repository\TagEvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tag/evenement')]
final class TagEvenementController extends AbstractController
{
    #[Route(name: 'app_tag_evenement_index', methods: ['GET'])]
    public function index(TagEvenementRepository $tagEvenementRepository): Response
    {
        return $this->render('tag_evenement/index.html.twig', [
            'tag_evenements' => $tagEvenementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tag_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tagEvenement = new TagEvenement();
        $form = $this->createForm(TagEvenementType::class, $tagEvenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tagEvenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_tag_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tag_evenement/new.html.twig', [
            'tag_evenement' => $tagEvenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tag_evenement_show', methods: ['GET'])]
    public function show(TagEvenement $tagEvenement): Response
    {
        return $this->render('tag_evenement/show.html.twig', [
            'tag_evenement' => $tagEvenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tag_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TagEvenement $tagEvenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TagEvenementType::class, $tagEvenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tag_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tag_evenement/edit.html.twig', [
            'tag_evenement' => $tagEvenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tag_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, TagEvenement $tagEvenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tagEvenement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tagEvenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tag_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
}
