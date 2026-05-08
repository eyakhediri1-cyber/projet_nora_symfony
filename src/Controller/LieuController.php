<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LieuController extends AbstractController
{
    #[Route('/lieux', name: 'app_lieux')]
    public function index(LieuRepository $lieuRepository): Response
    {
        $lieux = $lieuRepository->findAll();

        return $this->render('lieu/index.html.twig', [
            'lieux' => $lieux,
        ]);
    }

    #[Route('/lieux/nouveau', name: 'app_lieu_nouveau')]
    public function nouveau(Request $request, EntityManagerInterface $em): Response
    {
        $lieu = new Lieu();
        
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($lieu);
            $em->flush();

            $this->addFlash('success', '✅ Lieu créé avec succès !');
            return $this->redirectToRoute('app_lieux');
        }

        return $this->render('lieu/nouveau.html.twig', [
            'formulaire' => $form,
        ]);
    }
}
