<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Enums;

enum DataOutputModes: string
{
    case SPLITBYSOURCES = 'splitbysources';
    case COMBINED = 'combined';
}
