<?php

declare(strict_types=1);

namespace App\Application\Command\Model;

use App\Domain\Repository\ModelRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class DeleteCommandHandler
{
    public function __construct(
        private ModelRepositoryInterface $modelRepository,
    ) {
    }

    public function __invoke(DeleteCommand $command): JsonResponse
    {
        $model = $this->modelRepository->findById($command->id);

        if ($model == null) {
            throw new BadRequestHttpException('Model not found');
        }

        $this->modelRepository->remove($model);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
