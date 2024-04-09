<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route("/", name: "home")]
    public function index(): Response
    {
        $bikesLink = $this->generateUrl('api_bike_index');
        $enginesLink = $this->generateUrl('api_engine_index');
        $registerLink = $this->generateUrl('app_register');
        $loginLink = $this->generateUrl('app_login');
        $usersLink = $this->generateUrl('user_list');

        return $this->render('home/index.html.twig', [
            'bikesLink' => $bikesLink,
            'enginesLink' => $enginesLink,
            'registerLink' => $registerLink,
            'loginLink' => $loginLink,
            'usersLink' => $usersLink,
        ]);
    }
}
