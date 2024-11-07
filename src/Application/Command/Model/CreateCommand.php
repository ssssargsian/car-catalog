<?php

declare(strict_types=1);

namespace App\Application\Command\Model;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateCommand
{
    public function __construct(
        #[Assert\Length(min: 1, max: 255)]
        public string $name,
        #[Assert\Uuid(strict: false)]
        public string $brandId,
    ) { }
}
