<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Model;

interface ModelRepositoryInterface
{
    public function add(Model $model, bool $flush = true): void;
    public function remove(Model $model, bool $flush = true): void;
}
