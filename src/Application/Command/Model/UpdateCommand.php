<?php

declare(strict_types=1);

namespace App\Application\Command\Model;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateCommand
{
    public function __construct(
        #[Assert\Uuid(strict: false)]
        public string $id,
        #[Assert\Length(min: 1, max: 255)]
        public ?string $name = null,
        #[Assert\Uuid(strict: false)]
        public ?string $brandId = null,
    ) { }
}
