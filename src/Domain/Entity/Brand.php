<?php

namespace App\Domain\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use App\Application\Command\Brand\CreateCommand;
use App\Application\Command\Brand\UpdateCommand;
use App\Application\Controller\Brand\DeleteController;
use App\Infrastructure\Repository\BrandRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * @final
 */
#[ORM\Entity(repositoryClass: BrandRepository::class)]
#[ORM\Table(name: 'brands')]
#[ApiResource(
    operations: [
        new Get(
            openapi: new Operation(summary: 'Получить информацию по конкретному бренду'),
            normalizationContext: ['groups' => ['brand:read']],
        ),
        new GetCollection(
            openapi: new Operation(summary: 'Получить информацию по брендам'),
            normalizationContext: ['groups' => ['brand:read']],
        ),
        new Post(
            status: Response::HTTP_CREATED,
            openapi: new Operation(summary: 'Создание бренда'),
            normalizationContext: ['groups' => ['brand:read']],
            input: CreateCommand::class,
            output: self::class,
            messenger: 'input',
        ),
        new Patch(
            status: Response::HTTP_OK,
            openapi: new Operation(summary: 'Обновление бренда'),
            normalizationContext: ['groups' => ['brand:read']],
            input: UpdateCommand::class,
            output: self::class,
            messenger: 'input',
        ),
        new Delete(
            status: Response::HTTP_NO_CONTENT,
            controller: DeleteController::class,
            openapi: new Operation(summary: 'Удаление бренда'),
            normalizationContext: ['groups' => ['brand:read']],
            output: false,
        ),
    ],
    paginationClientEnabled: true,
    paginationEnabled: true,
)]
#[ApiFilter(SearchFilter::class, properties: [
    'name' => 'ipartial',
])]
class Brand
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['brand:read', 'model:read'])]
    private UuidInterface $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['brand:read', 'model:read'])]
    private string $name;

    public function __construct(
        string $name,
        ?UuidInterface $id = null,
    ) {
        $this->name = $name;

        $this->id = $id ?? Uuid::uuid7();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
