<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

class GraphQLSchemaHelpers
{
    /**
     * Convert the field type from its internal representation
     * to the GraphQL standard representation (eg: "[Post]")
     *
     * If $isNonNullableOrMandatory is `true`, a "!" is added to the type name,
     * to handle both field response and field arguments:
     *
     * - field response: isNonNullable
     * - field argument: isMandatory (its provided value can still be null)
     */
    public static function getTypeNameForGraphQLSchema(
        string $typeName,
        ?bool $isNonNullableOrMandatory = false,
        ?bool $isArray = false,
        ?bool $isNonNullArrayItems = false,
        ?bool $isArrayOfArrays = false,
        ?bool $isNonNullArrayOfArraysItems = false,
    ): string {
        // Wrap the type with the array brackets
        if ($isArrayOfArrays) {
            if ($isNonNullArrayOfArraysItems) {
                $typeName = self::getNonNullableOrMandatoryTypeName($typeName);
            }
            $typeName = self::getListTypeName($typeName);
        }
        if ($isArray) {
            if ($isNonNullArrayItems) {
                $typeName = self::getNonNullableOrMandatoryTypeName($typeName);
            }
            $typeName = self::getListTypeName($typeName);
        }
        if ($isNonNullableOrMandatory) {
            $typeName = self::getNonNullableOrMandatoryTypeName($typeName);
        }
        return $typeName;
    }

    public static function getNonNullableOrMandatoryTypeName(string $typeName): string
    {
        return sprintf(
            '%s!',
            $typeName
        );
    }

    public static function getListTypeName(string $typeName): string
    {
        return sprintf(
            '[%s]',
            $typeName
        );
    }
}
