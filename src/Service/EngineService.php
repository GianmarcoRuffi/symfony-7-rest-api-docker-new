<?php

namespace App\Service;

use App\Entity\Engine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EngineService
{
    private $entityManager;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function getAllEngines(): JsonResponse
    {
        try {
            $engines = $this->entityManager
                ->getRepository(Engine::class)
                ->findAll();

            $data = [];

            foreach ($engines as $engine) {
                $data[] = [
                    'id' => $engine->getSerialCode(),
                    'name' => $engine->getName(),
                    'serial_code' => $engine->getSerialCode(),
                    'horsepower' => $engine->getHorsepower(),
                    'manufacturer' => $engine->getManufacturer(),
                ];
            }

            return new JsonResponse($data);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createEngine(array $data): ?JsonResponse
    {
        $name = $data['name'];
        $serialCode = $data['serial_code'];
        $horsepower = $data['horsepower'];
        $manufacturer = $data['manufacturer'];

        if ($name === null || $serialCode === null || $horsepower === null || $manufacturer === null) {
            return new JsonResponse(['error' => 'Mandatory fields cannot be null.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $engine = new Engine();
        $engine->setName($name);
        $engine->setSerialCode($serialCode);
        $engine->setHorsepower((int)$horsepower);
        $engine->setManufacturer($manufacturer);

        $errors = $this->validator->validate($engine);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($engine);
        $this->entityManager->flush();

        $responseData = [
            'id' => $engine->getSerialCode(),
            'name' => $engine->getName(),
            'serial_code' => $engine->getSerialCode(),
            'horsepower' => $engine->getHorsepower(),
            'manufacturer' => $engine->getManufacturer(),
        ];

        return new JsonResponse($responseData, JsonResponse::HTTP_CREATED);
    }


    public function getEngineBySerialCode(string $serialCode): ?Engine
    {
        return $this->entityManager->getRepository(Engine::class)->findOneBy(['SerialCode' => $serialCode]);
    }


    public function updateEngine(string $serialCode, array $data): ?Engine
    {
        $engine = $this->entityManager->getRepository(Engine::class)->findOneBy(['SerialCode' => $serialCode]);

        if (!$engine) {
            return null;
        }

        if (isset($data['name'])) {
            $engine->setName($data['name']);
        }

        if (isset($data['serial_code'])) {
            $engine->setSerialCode($data['serial_code']);
        }

        if (isset($data['horsepower'])) {
            $engine->setHorsepower((int)$data['horsepower']);
        }

        if (isset($data['manufacturer'])) {
            $engine->setManufacturer($data['manufacturer']);
        }

        $errors = $this->validator->validate($engine);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            throw new \RuntimeException(json_encode(['errors' => $errorMessages], 400));
        }

        $this->entityManager->flush();

        return $engine;
    }



    public function deleteEngine(string $serialCode): bool
    {
        $engine = $this->entityManager->getRepository(Engine::class)->findOneBy(['SerialCode' => $serialCode]);

        if (!$engine) {
            return false;
        }

        $this->entityManager->remove($engine);
        $this->entityManager->flush();

        return true;
    }
}
