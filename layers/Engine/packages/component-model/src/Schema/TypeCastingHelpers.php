<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

class TypeCastingHelpers
{
    public const TYPE_SEPARATOR = ':';

    public static function combineTypes(...$types)
    {
        return implode(self::TYPE_SEPARATOR, $types);
    }

    public static function makeArray(string $type, $times = 1)
    {
        for ($i = 0; $i < $times; $i++) {
            $type = self::combineTypes(SchemaDefinition::TYPE_ARRAY, $type);
        }
        return $type;
    }
}
