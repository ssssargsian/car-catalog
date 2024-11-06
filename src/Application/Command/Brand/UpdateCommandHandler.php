<?php

declare(strict_types=1);

namespace App\Application\Command\Brand;

use App\Domain\Entity\Brand;
use App\Domain\Repository\BrandRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class UpdateCommandHandler
{
    public function __construct(
        private BrandRepositoryInterface $brandRepository,
    ) {
    }

    public function __invoke(UpdateCommand $command): Brand
    {
        $brand = $this->brandRepository->findById($command->id);
        if ($brand === null) {
            throw new BadRequestHttpException('Brand not found');
        }

        $brand->setName($command->name);
        $this->brandRepository->add($brand);

        return $brand;
    }
}
