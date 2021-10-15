<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Syntax;

class SyntaxHelpers
{
    /**
     * Indicate if the type if of type "LIST"
     */
    public static function isListWrappingType(string $typeNameOrID): bool
    {
        return substr($typeNameOrID, 0, 1) == '[' && substr($typeNameOrID, -1) == ']';
    }

    /**
     * Extract the nested types inside the list
     */
    public static function extractWrappedTypeFromListWrappingType(string $typeNameOrID): string
    {
        return substr($typeNameOrID, 1, strlen($typeNameOrID) - 2);
    }

    /**
     * Indicate if the type if of type "NON_NULL"
     */
    public static function isNonNullWrappingType(string $typeNameOrID): bool
    {
        return substr($typeNameOrID, -1) == '!';
    }

    /**
     * Extract the nested types which are "non null"
     */
    public static function extractWrappedTypeFromNonNullWrappingType(string $typeNameOrID): string
    {
        return substr($typeNameOrID, 0, strlen($typeNameOrID) - 1);
    }
}
