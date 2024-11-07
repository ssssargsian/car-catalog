<?php

namespace App\Domain\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use App\Application\Command\Specification\CreateCommand;
use App\Application\Command\Specification\UpdateCommand;
use App\Application\Controller\Specification\DeleteController;
use App\Domain\Enum\BodyType;
use App\Domain\Enum\DriveType;
use App\Domain\Enum\FuelType;
use App\Infrastructure\Repository\SpecificationRepository;
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
#[ORM\Entity(repositoryClass: SpecificationRepository::class)]
#[ORM\Table(name: 'specifications')]
#[ApiResource(
    operations: [
        new Get(
            openapi: new Operation(summary: 'Получить информацию по конкретной спецификации'),
            normalizationContext: ['groups' => ['specification:read']],
        ),
        new GetCollection(
            openapi: new Operation(summary: 'Получить список спецификаций'),
            normalizationContext: ['groups' => ['specification:read']],
        ),
        new Post(
            status: Response::HTTP_CREATED,
            openapi: new Operation(summary: 'Создание спецификации'),
            normalizationContext: ['groups' => ['specification:read']],
            input: CreateCommand::class,
            output: self::class,
            messenger: 'input',
        ),
        new Patch(
            status: Response::HTTP_OK,
            openapi: new Operation(summary: 'Обновление спецификации'),
            normalizationContext: ['groups' => ['specification:read']],
            input: UpdateCommand::class,
            output: self::class,
            messenger: 'input',
        ),
        new Delete(
            status: Response::HTTP_NO_CONTENT,
            controller: DeleteController::class,
            openapi: new Operation(summary: 'Удаление спецификации'),
            normalizationContext: ['groups' => ['specification:read']],
            output: false,
        ),
    ],
    paginationClientEnabled: true,
    paginationEnabled: true,
)]
#[ApiFilter(SearchFilter::class, properties: [
    'fuelType' => 'exact',
    'bodyType' => 'exact',
    'driveType' => 'exact',
])]
#[ApiFilter(RangeFilter::class, properties: [
    'engineVolume',
    'power',
    'fuelTankCapacity',
])]
#[ApiFilter(OrderFilter::class, properties: [
    'engineVolume',
    'power',
    'fuelTankCapacity',
], arguments: ['orderParameterName' => 'order'])]
class Specification
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['specification:read'])]
    private UuidInterface $id;

    #[ORM\Column(type: Types::STRING, length: 100, enumType: FuelType::class)]
    #[Groups(['specification:read'])]
    private FuelType $fuelType;

    #[ORM\Column(type: Types::FLOAT)]
    #[Groups(['specification:read'])]
    private float $engineVolume;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['specification:read'])]
    private int $power;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['specification:read'])]
    private int $fuelTankCapacity;

    #[ORM\Column(type: Types::STRING, length: 50, enumType: DriveType::class)]
    #[Groups(['specification:read'])]
    private DriveType $driveType;

    #[ORM\Column(type: Types::STRING, length: 50, enumType: BodyType::class)]
    #[Groups(['specification:read'])]
    private BodyType $bodyType;

    #[ORM\ManyToOne(targetEntity: Model::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['specification:read'])]
    private Model $model;

    public function __construct(
        Model $model,
        FuelType $fuelType,
        float $engineVolume,
        int $power,
        int $fuelTankCapacity,
        DriveType $driveType,
        BodyType $bodyType,
        ?UuidInterface $id = null,
    ) {
        $this->model = $model;
        $this->fuelType = $fuelType;
        $this->engineVolume = $engineVolume;
        $this->power = $power;
        $this->fuelTankCapacity = $fuelTankCapacity;
        $this->driveType = $driveType;
        $this->bodyType = $bodyType;
        $this->id = $id ?? Uuid::uuid7();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getFuelType(): FuelType
    {
        return $this->fuelType;
    }

    public function setFuelType(FuelType $fuelType): self
    {
        $this->fuelType = $fuelType;

        return $this;
    }

    public function getEngineVolume(): float
    {
        return $this->engineVolume;
    }

    public function setEngineVolume(float $engineVolume): self
    {
        $this->engineVolume = $engineVolume;

        return $this;
    }

    public function getPower(): int
    {
        return $this->power;
    }

    public function setPower(int $power): self
    {
        $this->power = $power;

        return $this;
    }

    public function getFuelTankCapacity(): int
    {
        return $this->fuelTankCapacity;
    }

    public function setFuelTankCapacity(int $fuelTankCapacity): self
    {
        $this->fuelTankCapacity = $fuelTankCapacity;

        return $this;
    }

    public function getDriveType(): DriveType
    {
        return $this->driveType;
    }

    public function setDriveType(DriveType $driveType): self
    {
        $this->driveType = $driveType;

        return $this;
    }

    public function getBodyType(): BodyType
    {
        return $this->bodyType;
    }

    public function setBodyType(BodyType $bodyType): self
    {
        $this->bodyType = $bodyType;

        return $this;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function setModel(Model $model): self
    {
        $this->model = $model;

        return $this;
    }
}
