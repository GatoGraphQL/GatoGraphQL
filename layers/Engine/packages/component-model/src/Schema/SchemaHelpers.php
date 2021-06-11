<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

class SchemaHelpers
{
    /**
     * Only validate that if the key is missing, and not if the value is empty,
     * because empty values could be allowed.
     *
     * Eg: `setTagsOnPost(tags:[])` where `tags` is mandatory
     */
    public static function getMissingFieldArgs(array $fieldArgProps, array $fieldArgs): array
    {
        return array_values(array_filter(
            $fieldArgProps,
            function ($fieldArgProp) use ($fieldArgs) {
                return !array_key_exists($fieldArgProp, $fieldArgs);
            }
        ));
    }

    public static function getSchemaMandatoryFieldOrDirectiveArgs(array $fieldOrDirectiveArgsSchemaDefinition)
    {
        return array_filter(
            $fieldOrDirectiveArgsSchemaDefinition,
            function ($fieldOrDirectiveArgSchemaDefinition) {
                return isset($fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_MANDATORY]) && $fieldOrDirectiveArgSchemaDefinition[SchemaDefinition::ARGNAME_MANDATORY];
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

    public static function getSchemaFieldArgNames(array $schemaFieldArgs)
    {
        // $schemaFieldArgs contains the name also as the key, keep only the values
        return array_values(array_map(
            function ($schemaFieldArg) {
                return $schemaFieldArg[SchemaDefinition::ARGNAME_NAME];
            },
            $schemaFieldArgs
        ));
    }

    public static function convertToSchemaFieldArgEnumValueDefinitions(array $enumValues)
    {
        $enumValueDefinitions = [];
        // Create an array representing the enumValue definition
        // Since only the enumValues were defined, these have no description/deprecated data, so no need to add these either
        foreach ($enumValues as $enumValue) {
            $enumValueDefinitions[$enumValue] = [
                SchemaDefinition::ARGNAME_NAME => $enumValue,
            ];
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
     * If the internal type is "id", convert it to its type name
     */
    public static function convertTypeIDToTypeName(
        string $type,
        TypeResolverInterface $typeResolver,
        string $fieldName
    ): string {
        // If the type is an ID, replace it with the actual type the ID references
        if ($type == SchemaDefinition::TYPE_ID) {
            $instanceManager = InstanceManagerFacade::getInstance();
            // The type may not be implemented yet (eg: Category), then skip
            if ($fieldTypeResolverClass = $typeResolver->resolveFieldTypeResolverClass($fieldName)) {
                $fieldTypeResolver = $instanceManager->getInstance((string)$fieldTypeResolverClass);
                $type = $fieldTypeResolver->getMaybeNamespacedTypeName();
            }
        }
        return $type;
    }

    public static function getSchemaNamespace(string $namespace): string
    {
        return self::convertNamespace(
            self::getOwnerAndProjectFromNamespace($namespace)
        );
    }

    /**
     * Following PSR-4, namespaces must contain the owner (eg: "PoP") and project name (eg: "ComponentModel")
     * Extract these 2 elements to namespace the types/interfaces
     */
    protected static function getOwnerAndProjectFromNamespace(string $namespace): string
    {
        // First slash: between owner and project name
        $firstSlashPos = strpos($namespace, '\\');
        if ($firstSlashPos !== false) {
            // Second slash: between project name and everything else
            $secondSlashPos = strpos($namespace, '\\', $firstSlashPos + strlen('\\'));
            if ($secondSlashPos !== false) {
                // Remove everything else
                return substr($namespace, 0, $secondSlashPos);
            }
        }
        return $namespace;
    }

    protected static function convertNamespace(string $namespace): string
    {
        return str_replace('\\', SchemaDefinition::TOKEN_NAMESPACE_SEPARATOR, $namespace);
    }

    public static function getSchemaNamespacedName(string $schemaNamespace, string $name): string
    {
        return ($schemaNamespace ? $schemaNamespace . SchemaDefinition::TOKEN_NAMESPACE_SEPARATOR : '') . $name;
    }
}
