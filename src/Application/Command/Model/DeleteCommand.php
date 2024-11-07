<?php

declare(strict_types=1);

namespace App\Application\Command\Model;

use Ramsey\Uuid\UuidInterface;

final readonly class DeleteCommand
{
    public function __construct(
        public UuidInterface $id,
    ) {
    }
}
