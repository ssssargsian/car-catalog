<?php

declare(strict_types=1);

namespace App\Application\Command\Brand;

use App\Domain\Repository\BrandRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class DeleteCommandHandler
{
    public function __construct(
        private BrandRepositoryInterface $brandRepository,
    ) {
    }

    public function __invoke(DeleteCommand $command): JsonResponse
    {
        $brand = $this->brandRepository->findById($command->id);

        if ($brand == null) {
            throw new BadRequestHttpException('Brand not found');
        }

        $this->brandRepository->remove($brand);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
