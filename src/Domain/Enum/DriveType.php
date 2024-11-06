<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum DriveType: string
{
    case FWD = 'fwd';
    case RWD = 'rwd';
    case AWD = 'awd';
}
