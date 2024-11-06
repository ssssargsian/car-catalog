<?php

declare(strict_types=1);

namespace App\Application\Command\Brand;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateCommand
{
    public function __construct(
        #[Assert\Uuid(strict: false)]
        public string $id,
        #[Assert\Length(min: 1, max: 255)]
        public string $name,
    ) { }
}
