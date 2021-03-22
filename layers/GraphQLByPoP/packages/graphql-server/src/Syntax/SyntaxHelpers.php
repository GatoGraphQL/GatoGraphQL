<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Syntax;

class SyntaxHelpers
{
    /**
     * Indicate if the type if of type "LIST"
     */
    public static function isListType(string $type): bool
    {
        return substr($type, 0, 1) == '[' && substr($type, -1) == ']';
    }

    /**
     * Extract the nested types inside the list
     */
    public static function getListTypeNestedTypeName(string $type): string
    {
        return substr($type, 1, strlen($type) - 2);
    }

    /**
     * Indicate if the type if of type "NON_NULL"
     */
    public static function isNonNullType(string $type): bool
    {
        return substr($type, -1) == '!';
    }

    /**
     * Extract the nested types which are "non null"
     */
    public static function getNonNullTypeNestedTypeName(string $type): string
    {
        return substr($type, 0, strlen($type) - 1);
    }
}
