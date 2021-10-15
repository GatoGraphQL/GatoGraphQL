<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;

class SchemaHelpers
{
    /**
     * Validate that if the key is missing or is `null`,
     * but not if the value is empty such as '""' or [],
     * because empty values could be allowed.
     *
     * Eg: `setTagsOnPost(tags:[])` where `tags` is mandatory
     */
    public static function getMissingFieldArgs(array $fieldArgNames, array $fieldArgs): array
    {
        return array_values(array_filter(
            $fieldArgNames,
            function ($fieldArgName) use ($fieldArgs) {
                return !isset($fieldArgs[$fieldArgName]);
            }
        ));
    }

    public static function getSchemaMandatoryFieldOrDirectiveArgs(array $fieldOrDirectiveArgsSchemaDefinition)
    {
        return array_filter(
            $fieldOrDirectiveArgsSchemaDefinition,
            function ($fieldOrDirectiveArgSchemaDefinition) {
                return $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::MANDATORY] ?? false;
            }
        );
    }

    public static function getEnumTypeFieldOrDirectiveArgsSchemaDefinition(array $fieldOrDirectiveArgsSchemaDefinition)
    {
        return array_filter(
            $fieldOrDirectiveArgsSchemaDefinition,
            fn ($fieldOrDirectiveArgSchemaDefinition) => $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::TYPE_RESOLVER] instanceof EnumTypeResolverInterface
        );
    }

    /**
     * @return array<string, EnumTypeResolverInterface>
     */
    public static function getSchemaFieldArgEnumTypeResolvers(array $schemaFieldArgs): array
    {
        return array_map(
            fn (array $schemaFieldArg): EnumTypeResolverInterface => $schemaFieldArg[SchemaDefinition::TYPE_RESOLVER],
            $schemaFieldArgs
        );
    }
}
