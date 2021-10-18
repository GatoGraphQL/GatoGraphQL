<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Syntax;

interface GraphQLSyntaxServiceInterface
{
    /**
     * Indicate if the type if of type "LIST"
     */
    public function isListWrappingType(string $typeNameOrID): bool;

    /**
     * Extract the nested types inside the list
     */
    public function extractWrappedTypeFromListWrappingType(string $typeNameOrID): string;

    /**
     * Indicate if the type if of type "NON_NULL"
     */
    public function isNonNullWrappingType(string $typeNameOrID): bool;

    /**
     * Extract the nested types which are "non null"
     */
    public function extractWrappedTypeFromNonNullWrappingType(string $typeNameOrID): string;
}
