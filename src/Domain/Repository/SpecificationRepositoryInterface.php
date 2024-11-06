<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Specification;

interface SpecificationRepositoryInterface
{
    public function add(Specification $specification, bool $flush = true): void;
    public function remove(Specification $specification, bool $flush = true): void;
}
