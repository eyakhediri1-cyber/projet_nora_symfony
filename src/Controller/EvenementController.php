<?php
namespace App\Controller;


use App\Service\FileUploader;
use App\Entity\Evenement;
use App\Entity\Inscription;
use App\Form\EvenementType;
use App\Form\InscriptionType;
use App\Repository\EvenementRepository;
use App\Service\EvenementManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EvenementController extends AbstractController
{
    public function __construct(private EvenementManager $eventManager) {}

    #[Route('/', name: 'app_accueil')]
    public function accueil(EvenementRepository $repo, RequestStack $rs): Response
    {
        $session = $rs->getSession();
        $ids = $session->get('recents', []);
        $recents = array_filter(array_map(fn($id) => $repo->find($id), $ids));

        return $this->render('evenement/accueil.html.twig', [
            'evenements' => $repo->findUpcoming(6),
            'recents'    => $recents,
        ]);
    }

    #[Route('/evenements', name: 'app_evenements')]
    public function index(Request $request, EvenementRepository $repo): Response
    {
        $evenements = $repo->findByFilters(
            $request->query->get('titre'),
            $request->query->get('categorie'),
            $request->query->get('ville'),
        );
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    #[Route('/evenements/nouveau', name: 'app_evenement_nouveau')]
#[IsGranted('ROLE_ORGANISATEUR')]
public function nouveau(Request $request, EntityManagerInterface $em, FileUploader $uploader): Response
{
    $evenement = new Evenement();
    $form = $this->createForm(EvenementType::class, $evenement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('imageName')->getData();
        if ($imageFile) {
            $evenement->setImageName($uploader->upload($imageFile));
        }
        $evenement->setOrganisateur($this->getUser());
        $em->persist($evenement);
        $em->flush();
        $this->addFlash('success', '✅ Événement créé !');
        return $this->redirectToRoute('app_evenements');
    }

    return $this->render('evenement/nouveau.html.twig', ['formulaire' => $form]);
}




    #[Route('/evenements/{id}', name: 'app_evenement_detail', requirements: ['id' => '\d+'])]
    public function detail(Evenement $evenement, RequestStack $rs): Response
    {
        // Session — derniers consultés
        $session = $rs->getSession();
        $recents = array_filter(
            $session->get('recents', []),
            fn($i) => $i !== $evenement->getId()
        );
        array_unshift($recents, $evenement->getId());
        $session->set('recents', array_slice($recents, 0, 5));

        return $this->render('evenement/detail.html.twig', [
            'evenement'       => $evenement,
            'placesRestantes' => $this->eventManager->getPlacesRestantes($evenement),
            'nbInscrits'      => $this->eventManager->getNbInscrits($evenement),
            'estInscrit'      => $this->getUser()
                ? $this->eventManager->estInscrit($this->getUser(), $evenement)
                : false,
        ]);
    }




    #[Route('/evenements/{id}/modifier', name: 'app_evenement_modifier', requirements: ['id' => '\d+'])]
public function modifier(Evenement $evenement, Request $request, EntityManagerInterface $em, FileUploader $uploader): Response
{
    if ($evenement->getOrganisateur() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN'))
        throw $this->createAccessDeniedException();

    $form = $this->createForm(EvenementType::class, $evenement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('imageName')->getData();
        if ($imageFile) {
            if ($evenement->getImageName()) {
                $uploader->remove($evenement->getImageName());
            }
            $evenement->setImageName($uploader->upload($imageFile));
        }
        $em->flush();
        $this->addFlash('success', '✏️ Modifié !');
        return $this->redirectToRoute('app_evenement_detail', ['id' => $evenement->getId()]);
    }

    return $this->render('evenement/modifier.html.twig', [
        'formulaire' => $form,
        'evenement'  => $evenement,
    ]);
}




    #[Route('/evenements/{id}/supprimer', name: 'app_evenement_supprimer', requirements: ['id' => '\d+'], methods: ['POST'])]
public function supprimer(Evenement $evenement, Request $request, EntityManagerInterface $em, FileUploader $uploader): Response
{
    if ($evenement->getOrganisateur() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN'))
        throw $this->createAccessDeniedException();

    if ($this->isCsrfTokenValid('supprimer_'.$evenement->getId(), $request->request->get('_token'))) {
        if ($evenement->getImageName()) {
            $uploader->remove($evenement->getImageName());
        }
        $em->remove($evenement);
        $em->flush();
        $this->addFlash('success', '🗑️ Supprimé.');
    }
    return $this->redirectToRoute('app_evenements');
}




    #[Route('/evenements/{id}/inscription', name: 'app_evenement_inscription', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
#[IsGranted('ROLE_USER')]
public function inscription(Evenement $evenement, Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
{
    $user = $this->getUser();


    // Vérifier si déjà inscrit
if ($this->eventManager->estInscrit($user, $evenement)) {
    $this->addFlash('warning', '⚠️ Vous êtes déjà inscrit !');
    return $this->redirectToRoute('app_evenement_detail', ['id' => $evenement->getId()]);
}
    $inscription = new Inscription();
    $inscription->setEvenement($evenement);
    $inscription->setParticipant($user);
    $inscription->setStatut('confirmee');
    $inscription->setDateInscription(new \DateTime());

    $form = $this->createForm(InscriptionType::class, $inscription);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($inscription);
        $em->flush();

        // Envoyer email de confirmation
        $email = (new TemplatedEmail())
            ->from('noreply@eventspot.com')
            ->to($user->getEmail())
            ->subject('🎫 Inscription confirmée : ' . $evenement->getTitre())
            ->htmlTemplate('emails/confirmation_inscription.html.twig')
            ->context([
                'evenement'    => $evenement,
                'participant'  => $user,
            ]);
        $mailer->send($email);

        $this->addFlash('success', '🎫 Inscription confirmée ! Un email vous a été envoyé.');
        return $this->redirectToRoute('app_evenement_detail', ['id' => $evenement->getId()]);
    }

    return $this->render('evenement/inscription.html.twig', [
        'formulaire' => $form,
        'evenement'  => $evenement,
    ]);
}
}