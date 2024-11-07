<?php

declare(strict_types=1);

namespace App\Application\Command\Model;

use App\Domain\Entity\Model;
use App\Domain\Repository\BrandRepositoryInterface;
use App\Domain\Repository\ModelRepositoryInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class CreateCommandHandler
{
    public function __construct(
        private BrandRepositoryInterface $brandRepository,
        private ModelRepositoryInterface $modelRepository,
    ) {
    }

    public function __invoke(CreateCommand $command): Model
    {
        $brand = $this->brandRepository->findById($command->brandId);
        if ($brand === null) {
            throw new BadRequestException('Brand not found');
        }

        $model = new Model(
            name: $command->name,
            brand:  $brand
        );
        $this->modelRepository->add($model);

        return $model;
    }
}
