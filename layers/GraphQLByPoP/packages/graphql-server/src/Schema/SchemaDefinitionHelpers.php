<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

use Exception;
use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;
use GraphQLByPoP\GraphQLServer\ObjectModels\Field;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;

class SchemaDefinitionHelpers
{
    public const PATH_SEPARATOR = '.';

    public static function getID(array $schemaDefinitionPath): string
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
     * If an ObjectType implements an interface, and the interface implements the same field,
     * then we must return the field definition as from the perspective of the interface!
     * Otherwise, when querying the schema in the GraphQL Playground (https://www.graphqlbin.com/v2/new/),
     * it produces this error from a mismatched type:
     * "Error: ContentEntry.status expects type "Interfaces_ContentEntry_Fields_Status"
     * but Post.status provides type "Types_Post_Fields_Status"."
     *
     * @deprecated 0.1.0 The error above must be fixed for the Enum, by unifying its name wherever it is referenced
     * This solution with the interfaces creates other issue: The type cannot customize its field schema definition
     */
    protected static function getInterfaceTypeFields(array &$fullSchemaDefinition, array $interfaceNames): array
    {
        $interfaceTypeFields = [];
        if ($interfaceNames) {
            foreach ($interfaceNames as $interfaceName) {
                $interfaceSchemaDefinition = $fullSchemaDefinition[SchemaDefinition::INTERFACES][$interfaceName];
                // If there is no definition for the interface, that means that it is not resolved by any ObjectTypeFieldResolver
                // That's an error, so throw a helpful exception
                if (is_null($interfaceSchemaDefinition)) {
                    $translationAPI = TranslationAPIFacade::getInstance();
                    throw new Exception(sprintf(
                        $translationAPI->__('Interface \'%s\' is not resolved by any ObjectTypeFieldResolver', 'graphql-server'),
                        $interfaceName
                    ));
                }
                $interfaceFields = array_keys($interfaceSchemaDefinition[SchemaDefinition::FIELDS]);
                // Watch out (again)! An interface can itself implement interfaces,
                // and a field can be shared across them
                // (eg: field "status" for interfaces CustomPost and ContentEntry)
                // Then, check if the interface's interface also implements the field!
                // Then, do not add it yet, leave it for its implemented interface to add it
                if ($interfaceImplementedInterfaceNames = $fullSchemaDefinition[SchemaDefinition::INTERFACES][$interfaceName][SchemaDefinition::INTERFACES] ?? []) {
                    $interfaceImplementedInterfaceFields = [];
                    foreach ($interfaceImplementedInterfaceNames as $interfaceImplementedInterfaceName) {
                        $interfaceImplementedInterfaceFields = array_merge(
                            $interfaceImplementedInterfaceFields,
                            array_keys($fullSchemaDefinition[SchemaDefinition::INTERFACES][$interfaceImplementedInterfaceName][SchemaDefinition::FIELDS])
                        );
                    }
                    $interfaceFields = array_diff(
                        $interfaceFields,
                        $interfaceImplementedInterfaceFields
                    );
                }
                // Set these fields as being defined by which interface
                foreach ($interfaceFields as $interfaceField) {
                    $interfaceTypeFields[$interfaceField] = $interfaceName;
                }
            }
        }
        return $interfaceTypeFields;
    }
    
    /**
     * @return Field[]
     */
    public static function initFieldsFromPath(array &$fullSchemaDefinition, array $fieldSchemaDefinitionPath): array
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
    
    /**
     * @return Field[]
     */
    public static function retrieveFieldsFromPath(array &$fullSchemaDefinition, array $fieldSchemaDefinitionPath): array
    {
        $fieldSchemaDefinitionPointer = self::advancePointerToPath($fullSchemaDefinition, $fieldSchemaDefinitionPath);
        $schemaDefinitionReferenceRegistry = SchemaDefinitionReferenceRegistryFacade::getInstance();
        $fields = [];
        foreach (array_keys($fieldSchemaDefinitionPointer) as $fieldName) {
            $schemaDefinitionReferenceObjectID = SchemaDefinitionHelpers::getID(array_merge(
                $fieldSchemaDefinitionPath,
                [
                    $fieldName
                ]
            ));
            /** @var Field */
            $field = $schemaDefinitionReferenceRegistry->getSchemaDefinitionReferenceObject($schemaDefinitionReferenceObjectID);
            $fields[] = $field;
        }
        return $fields;
    }
}
