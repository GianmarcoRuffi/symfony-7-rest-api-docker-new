<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserListController extends AbstractController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/users/{id}', name: 'user_info')]
    public function userInfo(int $id): Response
    {
        return $this->userService->getUserInfo($id);
    }

    #[Route('/users', name: 'user_list')]
    public function userList(): Response
    {
        return $this->userService->getAllUsers();
    }

    #[Route('/users/{id}/set-admin', name: 'set_admin')]
    public function setAdmin(int $id): Response
    {
        $this->userService->setUserRoleAdmin($id);
        return $this->redirectToRoute('user_list');
    }

    #[Route('/users/{id}/remove-admin', name: 'remove_admin')]
    public function removeAdmin(int $id): Response
    {
        $this->userService->removeUserRoleAdmin($id);
        return $this->redirectToRoute('user_list');
    }

    #[Route('/users/{id}/delete', name: 'delete_user')]
    public function deleteUser(int $id, UserService $userService): RedirectResponse
    {
        $userService->deleteUser($id);
        return $this->redirectToRoute('user_list');
    }
}
