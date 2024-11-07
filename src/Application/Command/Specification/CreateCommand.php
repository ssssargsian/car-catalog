<?php

declare(strict_types=1);

namespace App\Application\Command\Specification;

use App\Domain\Enum\BodyType;
use App\Domain\Enum\DriveType;
use App\Domain\Enum\FuelType;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateCommand
{
    public function __construct(
        #[Assert\Uuid(strict: false)]
        public string $modelId,
        #[Assert\NotBlank]
        public FuelType $fuelType,
        #[Assert\Positive]
        public float $engineVolume,
        #[Assert\Positive]
        public int $power,
        #[Assert\Positive]
        public int $fuelTankCapacity,
        #[Assert\NotBlank]
        public DriveType $driveType,
        #[Assert\NotBlank]
        public BodyType $bodyType,
    ) { }
}
