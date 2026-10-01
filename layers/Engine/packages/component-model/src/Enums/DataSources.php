<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Enums;

enum DataSources: string
{
    case IMMUTABLE = 'immutable';
    case MUTABLEONMODEL = 'mutableonmodel';
    case MUTABLEONREQUEST = 'mutableonrequest';
}
