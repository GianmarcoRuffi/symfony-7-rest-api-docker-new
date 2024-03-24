<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Bike;
use App\Entity\Engine;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\BikeService;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;


#[Route('/api', name: 'api_')]
class BikeController extends AbstractController
{
    private $bikeService;

    public function __construct(BikeService $bikeService)
    {
        $this->bikeService = $bikeService;
    }


    #[Route('/bikes', name: 'bike_index', methods: ['GET'])]
    public function index(): Response
    {
        $bikesData = $this->bikeService->getAllBikes();

        return $this->render('bike/index.html.twig', ['bikes' => $bikesData]);
    }


    #[Route('/bikes', name: 'bike_create', methods: ['post'])]
    public function create(): JsonResponse
    {
        return $this->bikeService->createBike();
    }


    #[Route('/bikes/{id}', name: 'bike_show', methods: ['GET'])]
    public function show(int $id, BikeService $bikeService): Response
    {
        $bikeData = $bikeService->getBikeById($id);

        if (!$bikeData) {
            throw $this->createNotFoundException('Bike not found');
        }

        return $this->render('bike/bike_show.html.twig', [
            'bike' => $bikeData,
        ]);
    }



    #[Route('/bikes/{id}', name: 'bike_update', methods: ['put', 'patch'])]
    public function update(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator, int $id): JsonResponse
    {
        $bike = $entityManager->getRepository(Bike::class)->find($id);

        if (!$bike) {
            return $this->json('No bike found for id: ' . $id, 404);
        }

        $brand = $request->request->get('brand');
        $color = $request->request->get('color');
        $engineSerial = $request->request->get('engine_serial');

        if ($brand !== null) {
            $bike->setBrand($brand);
        }
        if ($color !== null) {
            $bike->setColor($color);
        }
        if ($engineSerial !== null) {

            $engine = $entityManager->getRepository(Engine::class)->findOneBy(['SerialCode' => $engineSerial]);


            $bike->setEngine($engine);
        }


        $errors = $validator->validate($bike);


        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }


        $entityManager->flush();


        $data = [
            'id' => $bike->getId(),
            'brand' => $bike->getBrand(),
            'engine' => [
                'name' => $bike->getEngine()->getName(),
                'serial_code' => $bike->getEngine()->getSerialCode(),
                'manufacturer' => $bike->getEngine()->getManufacturer(),
                'horsepower' => $bike->getEngine()->getHorsepower(),
            ],
            'color' => $bike->getColor(),
        ];

        return $this->json($data);
    }


    #[Route('/bikes/{id}', name: 'bike_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $deleted = $this->bikeService->deleteBikeById($id);

        if (!$deleted) {
            return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
        }

        return new JsonResponse('The bike with the id ' . $id . ' has been successfully deleted');
    }
}
