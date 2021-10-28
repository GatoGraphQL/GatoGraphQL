<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

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
class GraphQLSchemaHelpers
{
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
