<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionTypes as GraphQLServerSchemaDefinitionTypes;
use PoP\ComponentModel\Schema\SchemaDefinitionTypes;

class SchemaHelpers
{
    /**
     * Convert the field type from its internal representation (eg: "array:id")
     * to the GraphQL standard representation (eg: "[Post]")
     *
     * If $isNonNullableOrMandatory is `true`, a "!" is added to the type name,
     * to handle both field response and field arguments:
     *
     * - field response: isNonNullable
     * - field argument: isMandatory (its provided value can still be null)
     */
    public static function getTypeToOutputInSchema(
        string $type,
        ?bool $isNonNullableOrMandatory = false,
        ?bool $isArray = false,
        ?bool $isNonNullArrayItems = false,
        ?bool $isArrayOfArrays = false,
        ?bool $isNonNullArrayOfArraysItems = false,
    ): string {
        // Convert the type name to standards by GraphQL
        $convertedType = self::convertTypeNameToGraphQLStandard($type);

        // Wrap the type with the array brackets
        if ($isArray) {
            if ($isArrayOfArrays) {
                $convertedType = sprintf(
                    '[%s%s]',
                    $convertedType,
                    $isNonNullArrayOfArraysItems ? '!' : ''
                );
            }
            $convertedType = sprintf(
                '[%s%s]',
                $convertedType,
                $isNonNullArrayItems ? '!' : ''
            );
        }
        if ($isNonNullableOrMandatory) {
            $convertedType = sprintf(
                '%s!',
                $convertedType
            );
        }
        return $convertedType;
    }
    public static function convertTypeNameToGraphQLStandard(string $typeName): string
    {
        // If the type is a scalar value, we need to convert it to the official GraphQL type
        $conversionTypes = [
            SchemaDefinitionTypes::TYPE_ID => GraphQLServerSchemaDefinitionTypes::TYPE_ID,
            SchemaDefinitionTypes::TYPE_STRING => GraphQLServerSchemaDefinitionTypes::TYPE_STRING,
            SchemaDefinitionTypes::TYPE_INT => GraphQLServerSchemaDefinitionTypes::TYPE_INT,
            SchemaDefinitionTypes::TYPE_FLOAT => GraphQLServerSchemaDefinitionTypes::TYPE_FLOAT,
            SchemaDefinitionTypes::TYPE_BOOL => GraphQLServerSchemaDefinitionTypes::TYPE_BOOL,
            SchemaDefinitionTypes::TYPE_OBJECT => GraphQLServerSchemaDefinitionTypes::TYPE_OBJECT,
            SchemaDefinitionTypes::TYPE_ANY_SCALAR => GraphQLServerSchemaDefinitionTypes::TYPE_ANY_SCALAR,
            SchemaDefinitionTypes::TYPE_MIXED => GraphQLServerSchemaDefinitionTypes::TYPE_MIXED,
            SchemaDefinitionTypes::TYPE_ARRAY_KEY => GraphQLServerSchemaDefinitionTypes::TYPE_ARRAY_KEY,
            SchemaDefinitionTypes::TYPE_DATE => GraphQLServerSchemaDefinitionTypes::TYPE_DATE,
            SchemaDefinitionTypes::TYPE_TIME => GraphQLServerSchemaDefinitionTypes::TYPE_TIME,
            SchemaDefinitionTypes::TYPE_URL => GraphQLServerSchemaDefinitionTypes::TYPE_URL,
            SchemaDefinitionTypes::TYPE_EMAIL => GraphQLServerSchemaDefinitionTypes::TYPE_EMAIL,
            SchemaDefinitionTypes::TYPE_IP => GraphQLServerSchemaDefinitionTypes::TYPE_IP,
            SchemaDefinitionTypes::TYPE_ENUM => GraphQLServerSchemaDefinitionTypes::TYPE_ENUM,
            SchemaDefinitionTypes::TYPE_INPUT_OBJECT => GraphQLServerSchemaDefinitionTypes::TYPE_INPUT_OBJECT,
        ];
        if (isset($conversionTypes[$typeName])) {
            $typeName = $conversionTypes[$typeName];
        }

        return $typeName;
    }
}
