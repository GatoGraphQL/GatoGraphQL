<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Enums\EnumTypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

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
                return $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_MANDATORY] ?? false;
            }
        );
    }

    public static function getEnumTypeFieldOrDirectiveArgsSchemaDefinition(array $fieldOrDirectiveArgsSchemaDefinition)
    {
        return array_filter(
            $fieldOrDirectiveArgsSchemaDefinition,
            fn ($fieldOrDirectiveArgSchemaDefinition) => $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_TYPE] == SchemaDefinition::TYPE_ENUM
        );
    }

    public static function convertToSchemaFieldArgEnumValueDefinitions(
        EnumTypeResolverInterface $enumTypeResolver,
    ): array {
        $enumValues = $enumTypeResolver->getEnumOutputValues();
        $enumValueDescriptions = $enumTypeResolver->getEnumValueDescriptions();
        $enumValueDefinitions = [];
        // Create an array representing the enumValue definition
        // Since only the enumValues were defined, these have no description/deprecated data, so no need to add these either
        foreach ($enumValues as $enumValue) {
            $enumValueDefinitions[$enumValue] = [
                SchemaDefinition::ARGNAME_NAME => $enumValue,
            ];
            if ($description = $enumValueDescriptions[$enumValue] ?? null) {
                $enumValueDefinitions[$enumValue][SchemaDefinition::ARGNAME_DESCRIPTION] = $description;
            }
        }
        return $enumValueDefinitions;
    }

    /**
     * Remove the deprecated enumValues from the schema definition
     */
    public static function removeDeprecatedEnumValuesFromSchemaDefinition(array $enumValueDefinitions): array
    {
        // Remove deprecated ones
        return array_filter(
            $enumValueDefinitions,
            function ($enumValueDefinition) {
                if ($enumValueDefinition[SchemaDefinition::ARGNAME_DEPRECATED] ?? null) {
                    return false;
                }
                return true;
            }
        );
    }

    public static function getSchemaFieldArgEnumValueDefinitions(array $schemaFieldArgs)
    {
        $enumValuesOrDefinitions = array_map(
            function ($schemaFieldArg) {
                return $schemaFieldArg[SchemaDefinition::ARGNAME_ENUM_VALUES];
            },
            $schemaFieldArgs
        );
        if (!$enumValuesOrDefinitions) {
            return [];
        }
        $enumValueDefinitions = [];
        foreach ($enumValuesOrDefinitions as $fieldArgName => $fieldArgEnumValuesOrDefinitions) {
            // The array is either an array of elemValues (eg: ["first", "second"]) or an array of elemValueDefinitions (eg: ["first" => ["name" => "first"], "second" => ["name" => "second"]])
            // To tell one from the other, check if the first element from the array is itself an array. In that case, it's a definition. Otherwise, it's already the value.
            $firstElemKey = key($fieldArgEnumValuesOrDefinitions);
            if (is_array($fieldArgEnumValuesOrDefinitions[$firstElemKey])) {
                $enumValueDefinitions[$fieldArgName] = $fieldArgEnumValuesOrDefinitions;
            } else {
                // Create an array representing the enumValue definition
                // Since only the enumValues were defined, these have no description/deprecated data, so no need to add these either
                foreach ($fieldArgEnumValuesOrDefinitions as $enumValue) {
                    $enumValueDefinitions[$fieldArgName][$enumValue] = [
                        SchemaDefinition::ARGNAME_NAME => $enumValue,
                    ];
                }
            }
        }
        return $enumValueDefinitions;
    }

    /**
     * Indicate if a FieldObjectTypeResolver class is of the Relational type
     */
    public static function isRelationalFieldTypeResolverClass(?string $fieldTypeResolverClass): ?bool
    {
        if ($fieldTypeResolverClass === null) {
            return null;
        }
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var TypeResolverInterface */
        $fieldTypeResolver = $instanceManager->getInstance($fieldTypeResolverClass);
        return $fieldTypeResolver instanceof RelationalTypeResolverInterface;
    }
}
