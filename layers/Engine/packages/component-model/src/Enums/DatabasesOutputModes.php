<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Enums;

enum DatabasesOutputModes: string
{
    case SPLITBYDATABASES = 'splitbydbs';
    case COMBINED = 'combined';
}
