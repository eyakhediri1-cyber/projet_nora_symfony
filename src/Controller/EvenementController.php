<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Inscription;
use App\Form\EvenementType;
use App\Form\InscriptionType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EvenementController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function accueil(EvenementRepository $evenementRepository): Response
    {
        // 6 prochains événements publiés
        $evenements = $evenementRepository->findUpcoming(6);

        return $this->render('evenement/accueil.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    #[Route('/evenements', name: 'app_evenements')]
    public function index(EvenementRepository $evenementRepository): Response
    {
        $evenements = $evenementRepository->findAll();

        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    #[Route('/evenements/nouveau', name: 'app_evenement_nouveau')]
    public function nouveau(Request $request, EntityManagerInterface $em): Response
    {
        $evenement = new Evenement();
        
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($evenement);
            $em->flush();

            $this->addFlash('success', '✅ Événement créé avec succès !');
            return $this->redirectToRoute('app_evenements');
        }

        return $this->render('evenement/nouveau.html.twig', [
            'formulaire' => $form,
        ]);
    }

    #[Route('/evenements/{id}', name: 'app_evenement_detail', requirements: ['id' => '\d+'])]
    public function detail(Evenement $evenement): Response
    {
        return $this->render('evenement/detail.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/evenements/{id}/modifier', name: 'app_evenement_modifier', requirements: ['id' => '\d+'])]
    public function modifier(Evenement $evenement, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', '✏️ Événement modifié avec succès !');
            return $this->redirectToRoute('app_evenement_detail', ['id' => $evenement->getId()]);
        }

        return $this->render('evenement/modifier.html.twig', [
            'formulaire' => $form,
            'evenement' => $evenement,
        ]);
    }

    #[Route('/evenements/{id}/supprimer', name: 'app_evenement_supprimer', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function supprimer(Evenement $evenement, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('supprimer_' . $evenement->getId(), $request->request->get('_token'))) {
            $em->remove($evenement);
            $em->flush();

            $this->addFlash('success', '🗑️ Événement supprimé avec succès.');
        } else {
            $this->addFlash('danger', '⚠️ Token CSRF invalide. Suppression annulée.');
        }

        return $this->redirectToRoute('app_evenements');
    }

    #[Route('/evenements/{id}/inscription', name: 'app_evenement_inscription', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function inscription(Evenement $evenement, Request $request, EntityManagerInterface $em): Response
    {
        $inscription = new Inscription();
        $inscription->setEvenement($evenement);
        
        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($inscription);
            $em->flush();

            $this->addFlash('success', '✅ Inscription confirmée !');
            return $this->redirectToRoute('app_evenement_detail', ['id' => $evenement->getId()]);
        }

        return $this->render('evenement/inscription.html.twig', [
            'formulaire' => $form,
            'evenement' => $evenement,
        ]);
    }
}
