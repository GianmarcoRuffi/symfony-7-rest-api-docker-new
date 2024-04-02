<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    #[Route("/", name: "home")]
    public function index(): Response
    {

        $bikesLink = $this->generateUrl('api_bike_index');
        $enginesLink = $this->generateUrl('api_engine_index');


        return $this->render('home/index.html.twig', [
            'bikesLink' => $bikesLink,
            'enginesLink' => $enginesLink,
        ]);
    }
}
