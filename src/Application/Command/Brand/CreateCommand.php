<?php

declare(strict_types=1);

namespace App\Application\Command\Brand;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateCommand
{
    public function __construct(
        #[Assert\Length(min: 1, max: 255)]
        public string $name,
    ) { }
}
