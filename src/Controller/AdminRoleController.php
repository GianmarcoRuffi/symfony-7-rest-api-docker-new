<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AddAdminRoleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminRoleController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/add-admin-role', name: 'add_admin_role')]
    public function addAdminRole(Request $request): Response
    {

        $userRepository = $this->entityManager->getRepository(User::class);
        $users = $userRepository->findAll();

        $form = $this->createForm(AddAdminRoleType::class, null, ['users' => $users,]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            if ($form->get('addAdminRole')->getData()) {
                $user->addRole('ROLE_ADMIN');
            }

            $this->entityManager->flush();

            $this->addFlash('success', 'Ruolo admin aggiunto con successo all\'utente');

            return $this->redirectToRoute('/add-admin-role');
        }

        return $this->render('admin_role/add_admin_role.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
