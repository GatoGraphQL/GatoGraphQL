<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

use PoP\ComponentModel\Schema\SchemaDefinition;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition as GraphQLServerSchemaDefinition;

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
    public static function getTypeToOutputInSchema(string $type, ?bool $isArray = false, ?bool $isNonNullArrayItems = false, ?bool $isNonNullableOrMandatory = false): string
    {
        // Convert the type name to standards by GraphQL
        $convertedType = self::convertTypeNameToGraphQLStandard($type);

        // Wrap the type with the array brackets
        if ($isArray) {
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
            SchemaDefinition::TYPE_ID => GraphQLServerSchemaDefinition::TYPE_ID,
            SchemaDefinition::TYPE_STRING => GraphQLServerSchemaDefinition::TYPE_STRING,
            SchemaDefinition::TYPE_INT => GraphQLServerSchemaDefinition::TYPE_INT,
            SchemaDefinition::TYPE_FLOAT => GraphQLServerSchemaDefinition::TYPE_FLOAT,
            SchemaDefinition::TYPE_BOOL => GraphQLServerSchemaDefinition::TYPE_BOOL,
            SchemaDefinition::TYPE_OBJECT => GraphQLServerSchemaDefinition::TYPE_OBJECT,
            SchemaDefinition::TYPE_ANY_SCALAR => GraphQLServerSchemaDefinition::TYPE_ANY_SCALAR,
            SchemaDefinition::TYPE_MIXED => GraphQLServerSchemaDefinition::TYPE_MIXED,
            SchemaDefinition::TYPE_ARRAY_KEY => GraphQLServerSchemaDefinition::TYPE_ARRAY_KEY,
            SchemaDefinition::TYPE_DATE => GraphQLServerSchemaDefinition::TYPE_DATE,
            SchemaDefinition::TYPE_TIME => GraphQLServerSchemaDefinition::TYPE_TIME,
            SchemaDefinition::TYPE_URL => GraphQLServerSchemaDefinition::TYPE_URL,
            SchemaDefinition::TYPE_EMAIL => GraphQLServerSchemaDefinition::TYPE_EMAIL,
            SchemaDefinition::TYPE_IP => GraphQLServerSchemaDefinition::TYPE_IP,
            SchemaDefinition::TYPE_ENUM => GraphQLServerSchemaDefinition::TYPE_ENUM,
            SchemaDefinition::TYPE_INPUT_OBJECT => GraphQLServerSchemaDefinition::TYPE_INPUT_OBJECT,
        ];
        if (isset($conversionTypes[$typeName])) {
            $typeName = $conversionTypes[$typeName];
        }

        return $typeName;
    }
}
