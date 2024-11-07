<?php

declare(strict_types=1);

namespace App\Application\Controller\Specification;

use App\Application\Command\Specification\DeleteCommand;
use App\Domain\Entity\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class DeleteController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
    ) {
    }

    public function __invoke(Model $model): Response
    {
        $envelope = $this->commandBus->dispatch(new DeleteCommand($model->getId()));

        $handledStamp = $envelope->last(HandledStamp::class);
        if ($handledStamp) {
            return $handledStamp->getResult();
        }

        return new Response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
