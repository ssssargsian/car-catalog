<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Domain\Repository\BrandRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class BrandTest extends WebTestCase
{
    private KernelBrowser $client;
    private BrandRepositoryInterface $brandRepository;
    private EntityManagerInterface $entityManager;
    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->brandRepository = $this->getContainer()->get(BrandRepositoryInterface::class);
        $this->entityManager = $this->getContainer()->get(EntityManagerInterface::class);
        $this->entityManager->beginTransaction();
    }

    public function testGetBrandById(): void
    {
        $brand = $this->brandRepository->findOneBy(['name' => 'Toyota']);
        $brandId = $brand->getId()->toString();

        $this->client->request('GET', sprintf('/brands/%s', $brandId));
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals($brandId, $data['id']);
        $this->assertEquals('Toyota', $data['name']);
    }

    public function testGetBrandList(): void
    {
        $this->client->request('GET', '/brands');
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);

        $this->assertIsArray($data);
        $this->assertGreaterThan(0, count($data));
    }

    public function testCreateBrandSuccess(): void
    {
        $this->client->request(
            'POST',
            '/brands',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Volkswagen'], JSON_THROW_ON_ERROR),
        );

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertEquals('Volkswagen', $data['name']);
    }

    public function testUpdateBrand(): void
    {
        $brand = $this->brandRepository->findOneBy(['name' => 'Toyota']);
        $brandId = $brand->getId()->toString();
        $this->client->request(
            'PATCH',
            "/brands/$brandId",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['id' => $brandId, 'name' => 'Toyota Updated'], JSON_THROW_ON_ERROR),
        );

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals($brandId, $data['id']);
        $this->assertEquals('Toyota Updated', $data['name']);
    }

    public function testDeleteBrand(): void
    {
        $brand = $this->brandRepository->findOneBy(['name' => 'Toyota']);
        $brandId = $brand->getId()->toString();

        $this->client->request('DELETE', "/brands/$brandId");
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
