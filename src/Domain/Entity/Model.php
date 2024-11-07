<?php

namespace App\Domain\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use App\Application\Command\Model\CreateCommand;
use App\Application\Command\Model\UpdateCommand;
use App\Application\Controller\Model\DeleteController;
use App\Infrastructure\Repository\ModelRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @final
 */
#[ORM\Entity(repositoryClass: ModelRepository::class)]
#[ORM\Table(name: 'models')]
#[ApiResource(
    operations: [
        new Get(
            openapi: new Operation(summary: 'Получить информацию по конкретной модели'),
            normalizationContext: ['groups' => ['model:read']],
        ),
        new GetCollection(
            openapi: new Operation(summary: 'Получить список моделей'),
            normalizationContext: ['groups' => ['model:read']],
        ),
        new Post(
            status: Response::HTTP_CREATED,
            openapi: new Operation(summary: 'Создание модели'),
            normalizationContext: ['groups' => ['model:read']],
            input: CreateCommand::class,
            output: self::class,
            messenger: 'input',
        ),
        new Patch(
            status: Response::HTTP_OK,
            openapi: new Operation(summary: 'Обновление модели'),
            normalizationContext: ['groups' => ['model:read']],
            input: UpdateCommand::class,
            output: self::class,
            messenger: 'input',
        ),
        new Delete(
            status: Response::HTTP_NO_CONTENT,
            controller: DeleteController::class,
            openapi: new Operation(summary: 'Удаление модели'),
            normalizationContext: ['groups' => ['model:read']],
            output: false,
        ),
    ],
    paginationClientEnabled: true,
    paginationEnabled: true,
)]
class Model
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['model:read'])]
    private UuidInterface $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['model:read'])]
    private string $name;

    #[ORM\ManyToOne(targetEntity: Brand::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['model:read'])]
    private Brand $brand;

    public function __construct(
        string $name,
        Brand $brand,
        ?UuidInterface $id = null,
    ) {
        $this->name = $name;
        $this->brand = $brand;

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

    public function getBrand(): Brand
    {
        return $this->brand;
    }

    public function setBrand(Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }
}
