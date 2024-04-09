<?php

// src/Service/UserService.php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class UserService
{
    private $entityManager;
    private $twig;

    public function __construct(EntityManagerInterface $entityManager, \Twig\Environment $twig)
    {
        $this->entityManager = $entityManager;
        $this->twig = $twig;
    }

    public function getUserInfo(int $id): Response
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find($id);

        if (!$user) {
            return new Response('User not found', Response::HTTP_NOT_FOUND);
        }

        return new Response(
            $this->twig->render('user_list/index.html.twig', [
                'user' => $user,
            ])
        );
    }

    public function getAllUsers(): Response
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $users = $userRepository->findAll();

        // Mapping dei ruoli degli utenti a nomi di ruoli leggibili
        foreach ($users as $user) {
            $user->readableRoles = $this->mapRolesToReadable($user->getRoles());
        }

        return new Response(
            $this->twig->render('user_list/index.html.twig', [
                'users' => $users,
            ])
        );
    }

    private function mapRolesToReadable(array $roles): array
    {
        $roleMap = [
            'ROLE_USER' => 'Utente',
            'ROLE_ADMIN' => 'Admin',
            // Aggiungi altri ruoli se necessario
        ];

        $readableRoles = [];

        foreach ($roles as $role) {
            if (isset($roleMap[$role])) {
                $readableRoles[] = $roleMap[$role];
            }
        }

        return $readableRoles;
    }

    public function setUserRoleAdmin(int $userId): void
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);

        if ($user) {
            $roles = $user->getRoles();
            if (!in_array('ROLE_ADMIN', $roles, true)) {
                $roles[] = 'ROLE_ADMIN';
                $user->setRoles(array_unique($roles));
                $this->entityManager->flush();
            }
        }
    }

    public function removeUserRoleAdmin(int $userId): void
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);

        if ($user) {
            $roles = $user->getRoles();
            $key = array_search('ROLE_ADMIN', $roles, true);
            if ($key !== false) {
                unset($roles[$key]);
                $user->setRoles(array_values($roles));
                $this->entityManager->flush();
            }
        }
    }
}

