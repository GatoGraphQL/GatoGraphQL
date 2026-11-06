<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\EnumType;

enum TestBackedEnum: string
{
    case FIRST = 'FIRST';
    case SECOND = 'SECOND';
}
