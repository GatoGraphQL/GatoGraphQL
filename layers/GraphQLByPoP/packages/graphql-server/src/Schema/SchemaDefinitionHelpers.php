<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

use GraphQLByPoP\GraphQLServer\ObjectModels\Field;

class SchemaDefinitionHelpers
{
    public const PATH_SEPARATOR = '.';

    public static function getSchemaDefinitionReferenceObjectID(array $schemaDefinitionPath): string
    {
        return implode(
            self::PATH_SEPARATOR,
            $schemaDefinitionPath
        );
    }
    public static function &advancePointerToPath(array &$schemaDefinition, array $schemaDefinitionPath)
    {
        $schemaDefinitionPointer = &$schemaDefinition;
        foreach ($schemaDefinitionPath as $pathLevel) {
            $schemaDefinitionPointer = &$schemaDefinitionPointer[$pathLevel];
        }
        return $schemaDefinitionPointer;
    }

    /**
     * @return Field[]
     */
    public static function createFieldsFromPath(array &$fullSchemaDefinition, array $fieldSchemaDefinitionPath): array
    {
        $fieldSchemaDefinitionPointer = self::advancePointerToPath($fullSchemaDefinition, $fieldSchemaDefinitionPath);
        $fields = [];
        foreach (array_keys($fieldSchemaDefinitionPointer) as $fieldName) {
            $fields[] = new Field(
                $fullSchemaDefinition,
                array_merge(
                    $fieldSchemaDefinitionPath,
                    [
                        $fieldName
                    ]
                )
            );
        }
        return $fields;
    }
}
