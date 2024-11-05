<?php

namespace App\Domain\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Infrastructure\Repository\BrandRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * @final
 */
#[ORM\Entity(repositoryClass: BrandRepository::class)]
#[ORM\Table(name: 'brands')]
#[ApiResource]
class Brand
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['brand:read'])]
    private UuidInterface $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['brand:read'])]
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
