<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Specification;
use Stringable;

interface SpecificationRepositoryInterface
{
    public function findById(Stringable|string $id);
    public function add(Specification $specification, bool $flush = true): void;
    public function remove(Specification $specification, bool $flush = true): void;
}
