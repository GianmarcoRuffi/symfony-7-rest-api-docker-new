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
    #[Route('/add-admin-role', name: 'add_admin_role')]
    public function addAdminRole(Request $request, EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        $form = $this->createForm(AddAdminRoleType::class, null, [
            'users' => $users,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $userId = $data['userId'];
            $addAdminRole = $data['addAdminRole'];

            // Trova l'utente nel database
            $user = $entityManager->getRepository(User::class)->find($userId);

            if (!$user) {
                throw $this->createNotFoundException('User not found');
            }

            // Aggiungi il ruolo "admin" all'utente se la checkbox è selezionata
            if ($addAdminRole) {
                $roles = $user->getRoles();
                $roles[] = 'ROLE_ADMIN';
                $user->setRoles(array_unique($roles));
                $entityManager->flush();

                $this->addFlash('success', 'Ruolo "admin" aggiunto con successo all\'utente.');
            } else {
                $this->addFlash('info', 'Nessun ruolo aggiunto.');
            }

            return $this->redirectToRoute('dashboard'); // Reindirizza alla pagina desiderata dopo l'aggiunta del ruolo
        }

        return $this->render('admin_role/add_admin_role.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
