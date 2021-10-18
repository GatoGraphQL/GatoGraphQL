<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Syntax;

class GraphQLSyntaxService implements GraphQLSyntaxServiceInterface
{
    /**
     * Indicate if the type if of type "LIST"
     */
    public function isListWrappingType(string $typeNameOrID): bool
    {
        return substr($typeNameOrID, 0, 1) == '[' && substr($typeNameOrID, -1) == ']';
    }

    /**
     * Extract the nested types inside the list
     */
    public function extractWrappedTypeFromListWrappingType(string $typeNameOrID): string
    {
        return substr($typeNameOrID, 1, strlen($typeNameOrID) - 2);
    }

    /**
     * Indicate if the type if of type "NON_NULL"
     */
    public function isNonNullWrappingType(string $typeNameOrID): bool
    {
        return substr($typeNameOrID, -1) == '!';
    }

    /**
     * Extract the nested types which are "non null"
     */
    public function extractWrappedTypeFromNonNullWrappingType(string $typeNameOrID): string
    {
        return substr($typeNameOrID, 0, strlen($typeNameOrID) - 1);
    }
}
