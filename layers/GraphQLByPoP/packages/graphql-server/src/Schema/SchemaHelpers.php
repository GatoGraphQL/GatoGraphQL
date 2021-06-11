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
    public static function getTypeToOutputInSchema(string $type, ?bool $isArray = false, ?bool $isNonNullableOrMandatory = false): string
    {
        // Convert the type name to standards by GraphQL
        $convertedType = self::convertTypeNameToGraphQLStandard($type);

        return self::convertTypeToSDLSyntax($isArray ? 1 : 0, $convertedType, $isNonNullableOrMandatory);
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
            SchemaDefinition::TYPE_MIXED => GraphQLServerSchemaDefinition::TYPE_MIXED,
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
    protected static function convertTypeToSDLSyntax(int $arrayInstances, string $convertedType, ?bool $isNonNullableOrMandatory = false): string
    {
        // Wrap the type with the array brackets
        for ($i = 0; $i < $arrayInstances; $i++) {
            $convertedType = sprintf(
                '[%s]',
                $convertedType
            );
        }
        if ($isNonNullableOrMandatory) {
            $convertedType .= '!';
        }
        return $convertedType;
    }
}
