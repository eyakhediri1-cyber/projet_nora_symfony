<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/users', name: 'app_admin_users')]
    public function users(UserRepository $userRepository): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/users/{id}/role', name: 'app_admin_user_role', methods: ['POST'])]
    public function changeRole(User $user, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('change_role_' . $user->getId(), $request->request->get('_token'))) {
            $role = $request->request->get('role');

            // ✅ Seulement ROLE_USER ou ROLE_ADMIN autorisés
            if (in_array($role, ['ROLE_USER', 'ROLE_ADMIN'])) {
                $user->setRoles([$role]);
                $em->flush();
                $this->addFlash('success', 'Rôle mis à jour avec succès.');
            }
        }

        return $this->redirectToRoute('app_admin_users');
    }
}