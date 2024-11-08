<?php

declare(strict_types=1);

namespace App\Application\Controller\Brand;

use App\Application\Command\Brand\DeleteCommand;
use App\Domain\Entity\Brand;
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

    public function __invoke(Brand $brand): Response
    {
        $envelope = $this->commandBus->dispatch(new DeleteCommand($brand->getId()));
        // Извлекаем ответ из обработанного штампа
        $handledStamp = $envelope->last(HandledStamp::class);
        if ($handledStamp) {
            return $handledStamp->getResult();
        }

        return new Response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
