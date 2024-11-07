<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class BrandTest extends WebTestCase
{
    private KernelBrowser $client;
    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);

        // Очистка и создание новой схемы базы данных для тестов
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    private function createBrand(): string
    {
        $this->client->request(
            'POST',
            '/brands',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Toyota'], JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertEquals('Toyota', $data['name']);

        return $data['id'];
    }

    public function testCreateBrandSuccess(): void
    {
        $this->createBrand();
    }

    public function testGetBrandList(): void
    {
        $this->createBrand();

        $this->client->request('GET', '/brands');
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertGreaterThan(0, count($data));
    }

    public function testGetBrandById(): void
    {
        $brandId = $this->createBrand();

        $this->client->request('GET', "/brands/{$brandId}");
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals($brandId, $data['id']);
        $this->assertEquals('Toyota', $data['name']);
    }

    public function testUpdateBrand(): void
    {
        $brandId = $this->createBrand();

        $this->client->request(
            'PATCH',
            "/brands/$brandId",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['id' => $brandId, 'name' => 'Toyota Updated'], JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals($brandId, $data['id']);
        $this->assertEquals('Toyota Updated', $data['name']);
    }

    public function testDeleteBrand(): void
    {
        $brandId = $this->createBrand();

        $this->client->request('DELETE', "/brands/{$brandId}");
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());

        $this->client->request('GET', "/brands/{$brandId}");
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
        parent::tearDown();
    }
}
