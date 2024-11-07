<?php

declare(strict_types=1);

namespace App\Application\Command\Specification;

use App\Domain\Enum\BodyType;
use App\Domain\Enum\DriveType;
use App\Domain\Enum\FuelType;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateCommand
{
    public function __construct(
        #[Assert\Uuid(strict: false)]
        public string $id,
        #[Assert\Uuid(strict: false)]
        public ?string $modelId = null,
        public ?FuelType $fuelType = null,
        public ?float $engineVolume = null,
        public ?int $power = null,
        public ?int $fuelTankCapacity = null,
        public ?DriveType $driveType = null,
        public ?BodyType $bodyType = null,
    ) { }
}
