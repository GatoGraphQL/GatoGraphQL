<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

class DefaultDataStructureFormatter extends AbstractJSONDataStructureFormatter
{
    public const NAME = 'default';

    public static function getName(): string
    {
        return self::NAME;
    }
}
