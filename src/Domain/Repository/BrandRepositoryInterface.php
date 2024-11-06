<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Brand;
use Stringable;

interface BrandRepositoryInterface
{
    public function findById(Stringable|string $id);
    public function add(Brand $brand, bool $flush = true): void;
    public function remove(Brand $brand, bool $flush = true): void;
}
