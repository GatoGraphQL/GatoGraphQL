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

    /**
     * Return the current type combination element, which is simply the first element, always
     * Eg: if passing "string", it is "string"; for "array:string", it is "array";
     */
    public static function getTypeCombinationCurrentElement(string $type): string
    {
        $maybeCombinationElems = explode(self::TYPE_SEPARATOR, $type);
        return $maybeCombinationElems[0];
    }
    /**
     * If the type is a combination of 2 or more types, then return the string containing all of them except the first one
     * Eg: if passing "string", it is null; for "array:string", it is "string"; for "array:array:string", it is "array:string"
     */
    public static function getTypeCombinationNestedElements(string $type): ?string
    {
        $maybeCombinationElems = explode(self::TYPE_SEPARATOR, $type);
        if (count($maybeCombinationElems) >= 2) {
            // Remove the first element
            return substr($type, strlen($maybeCombinationElems[0]) + 1);
        }
        // There are no others
        return null;
    }
}
