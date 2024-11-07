<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Model;
use Stringable;

interface ModelRepositoryInterface
{
    public function findById(Stringable|string $id);
    public function add(Model $model, bool $flush = true): void;
    public function remove(Model $model, bool $flush = true): void;
}
