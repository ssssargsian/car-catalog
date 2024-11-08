<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Domain\Enum\BodyType;
use App\Domain\Enum\DriveType;
use App\Domain\Enum\FuelType;
use App\Domain\Repository\ModelRepositoryInterface;
use App\Domain\Repository\SpecificationRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class SpecificationTest extends WebTestCase
{
    private KernelBrowser $client;
    private ModelRepositoryInterface $modelRepository;
    private SpecificationRepositoryInterface $specificationRepository;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->modelRepository = $this->getContainer()->get(ModelRepositoryInterface::class);
        $this->specificationRepository = $this->getContainer()->get(SpecificationRepositoryInterface::class);
        $this->entityManager = $this->getContainer()->get(EntityManagerInterface::class);
        $this->entityManager->beginTransaction();
    }

    public function testGetSpecificationById(): void
    {
        $specification = $this->specificationRepository->findOneBy(['power' => 140]);
        $specificationId = $specification->getId()->toString();

        $this->client->request('GET', sprintf('/specifications/%s', $specificationId));
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals($specificationId, $data['id']);
        $this->assertEquals(140, $data['power']);
    }

    public function testGetSpecificationList(): void
    {
        $this->client->request('GET', '/specifications');
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertGreaterThan(0, count($data));
    }

    public function testCreateSpecificationSuccess(): void
    {
        $model = $this->modelRepository->findOneBy(['name' => 'Camry']);
        $modelId = $model->getId()->toString();

        $this->client->request(
            'POST',
            '/specifications',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'modelId' => $modelId,
                'fuelType' => FuelType::GASOLINE,
                'engineVolume' => 2.5,
                'power' => 200,
                'fuelTankCapacity' => 60,
                'driveType' => DriveType::FWD,
                'bodyType' => BodyType::SEDAN
            ], JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertEquals(200, $data['power']);
    }

    public function testUpdateSpecification(): void
    {
        $specification = $this->specificationRepository->findOneBy(['power' => 140]);
        $specificationId = $specification->getId()->toString();

        $this->client->request(
            'PATCH',
            "/specifications/$specificationId",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['id' => $specificationId, 'bodyType' => BodyType::COUPE], JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals($specificationId, $data['id']);
        $this->assertEquals(BodyType::COUPE->value, $data['bodyType']);
    }

    public function testDeleteSpecification(): void
    {
        $specification = $this->specificationRepository->findOneBy(['power' => 140]);
        $specificationId = $specification->getId()->toString();

        $this->client->request('DELETE', "/specifications/$specificationId");
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    protected function tearDown(): void
    {
        if ($this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->rollback();
        }

        parent::tearDown();
    }
}
