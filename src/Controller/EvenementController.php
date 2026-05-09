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
use Symfony\Component\Security\Http\Attribute\IsGranted; // Indispensable pour #[IsGranted]

use App\Service\EvenementManager;

class EvenementController extends AbstractController
{
    public function __construct(
        private EvenementManager $eventManager
    ) {}
    #[Route('/', name: 'app_accueil')]
    public function accueil(EvenementRepository $evenementRepository): Response
    {
        // On récupère les 6 prochains événements (méthode personnalisée dans ton Repository)
        $evenements = $evenementRepository->findBy([], ['dateDebut' => 'ASC'], 6);

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
    #[IsGranted('ROLE_ORGANISATEUR')] // 🔒 Réservé aux organisateurs et admins
    public function nouveau(Request $request, EntityManagerInterface $em): Response
    {
        $evenement = new Evenement();
        
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ✅ AUTOMATISATION : On définit l'utilisateur connecté comme organisateur
            $evenement->setOrganisateur($this->getUser());
            
            // ✅ AUTOMATISATION : On définit la date de création au moment présent
            $evenement->setDateCreation(new \DateTime());

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
    $placesRestantes = $this->eventManager->getPlacesRestantes($evenement);
    $nbInscrits = $this->eventManager->getNbInscrits($evenement);

    return $this->render('evenement/detail.html.twig', [
        'evenement' => $evenement,
        'placesRestantes' => $placesRestantes,
        'nbInscrits' => $nbInscrits,
    ]);
}
    


    #[Route('/evenements/{id}/modifier', name: 'app_evenement_modifier', requirements: ['id' => '\d+'])]
    public function modifier(Evenement $evenement, Request $request, EntityManagerInterface $em): Response
    {
        // 🔒 VÉRIFICATION DE PROPRIÉTÉ : 
        // Si l'utilisateur n'est pas l'orga de l'event ET n'est pas Admin -> Accès refusé
        if ($evenement->getOrganisateur() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException("Vous n'avez pas le droit de modifier cet événement.");
        }

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
        // 🔒 VÉRIFICATION DE PROPRIÉTÉ (identique à la modification)
        if ($evenement->getOrganisateur() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException("Action interdite.");
        }

        if ($this->isCsrfTokenValid('supprimer_' . $evenement->getId(), $request->request->get('_token'))) {
            $em->remove($evenement);
            $em->flush();

            $this->addFlash('success', '🗑️ Événement supprimé avec succès.');
        } else {
            $this->addFlash('danger', '⚠️ Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_evenements');
    }

    #[Route('/evenements/{id}/inscription', name: 'app_evenement_inscription', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')] // 🔒 Obligation d'être connecté pour s'inscrire
    public function inscription(Evenement $evenement, Request $request, EntityManagerInterface $em): Response
    {
        // Symfony garantit ici que $this->getUser() existe grâce au IsGranted
        $user = $this->getUser();

        $inscription = new Inscription();
        $inscription->setEvenement($evenement);
        $inscription->setParticipant($user);
        $inscription->setStatut('en_attente'); // Statut par défaut
        $inscription->setDateInscription(new \DateTime()); // Date auto
        
        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($inscription);
            $em->flush();

            $this->addFlash('success', '✅ Votre demande d\'inscription a été enregistrée !');
            return $this->redirectToRoute('app_evenement_detail', ['id' => $evenement->getId()]);
        }

        return $this->render('evenement/inscription.html.twig', [
            'formulaire' => $form,
            'evenement' => $evenement,
        ]);
    }
}