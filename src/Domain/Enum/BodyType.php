<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum BodyType: string
{
    case SEDAN = 'sedan';
    case SUV = 'suv';
    case HATCHBACK = 'hatchback';
    case COUPE = 'coupe';
    case CONVERTIBLE = 'convertible';
}
