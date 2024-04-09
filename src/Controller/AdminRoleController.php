<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminRoleController extends AbstractController
{
    #[Route('/add-admin-role/{userId}', name: 'add_admin_role', methods: ['POST'])]
    public function addAdminRole(int $userId, EntityManagerInterface $entityManager): Response
    {
        // Trova l'utente nel database
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        // Aggiungi il ruolo "admin" all'utente
        $roles = $user->getRoles();
        $roles[] = 'ROLE_ADMIN'; // Aggiungi il ruolo
        $user->setRoles(array_unique($roles)); // Assicurati che i ruoli siano unici
        $entityManager->flush();

        return new JsonResponse(['message' => 'Ruolo "admin" aggiunto con successo all\'utente.'], Response::HTTP_OK);
    }
}
