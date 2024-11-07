<?php

declare(strict_types=1);

namespace App\Application\Command\Specification;

use App\Domain\Repository\SpecificationRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class DeleteCommandHandler
{
    public function __construct(
        private SpecificationRepositoryInterface $specificationRepository,
    ) {
    }

    public function __invoke(DeleteCommand $command): JsonResponse
    {
        $specification = $this->specificationRepository->findById($command->id);

        if ($specification == null) {
            throw new BadRequestHttpException('Specification not found');
        }

        $this->specificationRepository->remove($specification);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
