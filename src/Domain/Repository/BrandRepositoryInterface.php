<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Brand;

interface BrandRepositoryInterface
{
    public function add(Brand $brand, bool $flush = true): void;
    public function remove(Brand $brand, bool $flush = true): void;
}
