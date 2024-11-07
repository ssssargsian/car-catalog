<?php

declare(strict_types=1);

namespace App\Application\Command\Specification;

use App\Domain\Entity\Specification;
use App\Domain\Repository\SpecificationRepositoryInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class UpdateCommandHandler
{
    public function __construct(
        private SpecificationRepositoryInterface $specificationRepository,
    ) {
    }

    public function __invoke(UpdateCommand $command): Specification
    {
        $specification = $this->specificationRepository->findById($command->id);
        if ($specification === null) {
            throw new BadRequestException('Specification not found');
        }

        if ($command->fuelType !== null) {
            $specification->setFuelType($command->fuelType);
        }

        if ($command->engineVolume !== null) {
            $specification->setEngineVolume($command->engineVolume);
        }

        if ($command->power !== null) {
            $specification->setPower($command->power);
        }

        if ($command->fuelTankCapacity !== null) {
            $specification->setFuelTankCapacity($command->fuelTankCapacity);
        }

        if ($command->driveType !== null) {
            $specification->setDriveType($command->driveType);
        }

        if ($command->bodyType !== null) {
            $specification->setBodyType($command->bodyType);
        }

        $this->specificationRepository->add($specification);

        return $specification;
    }
}
