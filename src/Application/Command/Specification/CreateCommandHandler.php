<?php

declare(strict_types=1);

namespace App\Application\Command\Specification;

use App\Domain\Entity\Specification;
use App\Domain\Repository\ModelRepositoryInterface;
use App\Domain\Repository\SpecificationRepositoryInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class CreateCommandHandler
{
    public function __construct(
        private ModelRepositoryInterface $modelRepository,
        private SpecificationRepositoryInterface $specificationRepository,
    ) {
    }

    public function __invoke(CreateCommand $command): Specification
    {
        $model = $this->modelRepository->findById($command->modelId);
        if ($model === null) {
            throw new BadRequestException('Model not found');
        }

        $specification = new Specification(
            model: $model,
            fuelType: $command->fuelType,
            engineVolume: $command->engineVolume,
            power: $command->power,
            fuelTankCapacity: $command->fuelTankCapacity,
            driveType: $command->driveType,
            bodyType: $command->bodyType
        );

        $this->specificationRepository->add($specification);

        return $specification;
    }
}
