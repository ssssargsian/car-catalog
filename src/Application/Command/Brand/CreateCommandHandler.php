<?php

declare(strict_types=1);

namespace App\Application\Command\Brand;

use App\Domain\Entity\Brand;
use App\Domain\Repository\BrandRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class CreateCommandHandler
{
    public function __construct(
        private BrandRepositoryInterface $brandRepository,
    ) {
    }

    public function __invoke(CreateCommand $command): Brand
    {
        $brand = new Brand(name: $command->name);

        $this->brandRepository->add($brand);

        return $brand;
    }
}
