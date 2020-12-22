<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Syntax;

class SyntaxHelpers
{
    /**
     * Indicate if the type if of type "LIST"
     *
     * @param string $type
     * @return boolean
     */
    public static function isListType(string $type): bool
    {
        return substr($type, 0, 1) == '[' && substr($type, -1) == ']';
    }

    /**
     * Extract the nested types inside the list
     *
     * @param string $type
     * @return string
     */
    public static function getListTypeNestedTypeName(string $type): string
    {
        return substr($type, 1, strlen($type) - 2);
    }

    /**
     * Indicate if the type if of type "NON_NULL"
     *
     * @param string $type
     * @return boolean
     */
    public static function isNonNullType(string $type): bool
    {
        return substr($type, -1) == '!';
    }

    /**
     * Extract the nested types which are "non null"
     *
     * @param string $type
     * @return string
     */
    public static function getNonNullTypeNestedTypeName(string $type): string
    {
        return substr($type, 0, strlen($type) - 1);
    }
}
