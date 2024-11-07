<?php

declare(strict_types=1);

namespace App\Application\Command\Model;

use App\Domain\Entity\Model;
use App\Domain\Repository\BrandRepositoryInterface;
use App\Domain\Repository\ModelRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class UpdateCommandHandler
{
    public function __construct(
        private BrandRepositoryInterface $brandRepository,
        private ModelRepositoryInterface $modelRepository,
    ) {
    }

    public function __invoke(UpdateCommand $command): Model
    {
        $model = $this->modelRepository->findById($command->id);
        if ($model === null) {
            throw new BadRequestHttpException('Model not found');
        }

        if ($command->name !== null) {
            $model->setName($command->name);
        }
        if ($command->brandId !== null) {
            $brand = $this->brandRepository->findById($command->brandId);
            $model->setBrand($brand);
        }

        $this->modelRepository->add($model);

        return $model;
    }
}
