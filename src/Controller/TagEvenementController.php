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

class TagEvenementController extends AbstractController
{
    #[Route('/tags', name: 'app_tags')]
    public function index(TagEvenementRepository $tagRepository): Response
    {
        $tags = $tagRepository->findAll();

        return $this->render('tag_evenement/index.html.twig', [
            'tags' => $tags,
        ]);
    }

    #[Route('/tags/nouveau', name: 'app_tag_nouveau')]
    public function nouveau(Request $request, EntityManagerInterface $em): Response
    {
        $tag = new TagEvenement();
        
        $form = $this->createForm(TagEvenementType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tag);
            $em->flush();

            $this->addFlash('success', '✅ Tag créé avec succès !');
            return $this->redirectToRoute('app_tags');
        }

        return $this->render('tag_evenement/nouveau.html.twig', [
            'formulaire' => $form,
        ]);
    }

    #[Route('/tags/{id}/supprimer', name: 'app_tag_supprimer', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function supprimer(TagEvenement $tag, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('supprimer_tag_' . $tag->getId(), $request->request->get('_token'))) {
            $em->remove($tag);
            $em->flush();

            $this->addFlash('success', '🗑️ Tag supprimé avec succès.');
        } else {
            $this->addFlash('danger', '⚠️ Token CSRF invalide. Suppression annulée.');
        }

        return $this->redirectToRoute('app_tags');
    }
}
