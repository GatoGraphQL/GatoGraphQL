<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Enums;

enum DataSourceSelectors: string
{
    case ONLYMODEL = 'onlymodel';
    case MODELANDREQUEST = 'modelandrequest';
}
