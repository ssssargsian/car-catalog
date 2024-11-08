<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Domain\Repository\BrandRepositoryInterface;
use App\Domain\Repository\ModelRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class ModelTest extends WebTestCase
{
    private KernelBrowser $client;
    private ModelRepositoryInterface $modelRepository;
    private BrandRepositoryInterface $brandRepository;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->modelRepository = $this->getContainer()->get(ModelRepositoryInterface::class);
        $this->brandRepository = $this->getContainer()->get(BrandRepositoryInterface::class);
        $this->entityManager = $this->getContainer()->get(EntityManagerInterface::class);
        $this->entityManager->beginTransaction();
    }

    public function testGetModelById(): void
    {
        $model = $this->modelRepository->findOneBy(['name' => 'Camry']);
        $modelId = $model->getId()->toString();

        $this->client->request('GET', sprintf('/models/%s', $modelId));
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals($modelId, $data['id']);
        $this->assertEquals('Camry', $data['name']);
    }

    public function testGetModelList(): void
    {
        $this->client->request('GET', '/models');
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertGreaterThan(0, count($data));
    }

    public function testCreateModelSuccess(): void
    {
        $brand = $this->brandRepository->findOneBy(['name' => 'Toyota']);
        $brandId = $brand->getId()->toString();

        $this->client->request(
            'POST',
            '/models',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Camry', 'brandId' => $brandId], JSON_THROW_ON_ERROR),
        );

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $data);
        $this->assertEquals('Camry', $data['name']);
        $this->assertEquals('Toyota', $data['brand']['name']);
    }

    public function testUpdateModel(): void
    {
        $model = $this->modelRepository->findOneBy(['name' => 'Camry']);
        $modelId = $model->getId()->toString();

        $this->client->request(
            'PATCH',
            "/models/$modelId",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['id' => $modelId, 'name' => 'Camry Updated'], JSON_THROW_ON_ERROR),
        );

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals($modelId, $data['id']);
        $this->assertEquals('Camry Updated', $data['name']);
    }

    public function testDeleteModel(): void
    {
        $model = $this->modelRepository->findOneBy(['name' => 'Camry']);
        $modelId = $model->getId()->toString();

        $this->client->request('DELETE', "/models/$modelId");
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
