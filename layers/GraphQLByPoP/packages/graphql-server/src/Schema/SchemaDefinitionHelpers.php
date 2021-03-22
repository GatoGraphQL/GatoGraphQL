<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

use Exception;
use GraphQLByPoP\GraphQLServer\Environment;
use GraphQLByPoP\GraphQLServer\ObjectModels\Field;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;

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
    protected static function getFieldInterfaces(array &$fullSchemaDefinition, array $interfaceNames): array
    {
        $fieldInterfaces = [];
        if ($interfaceNames) {
            foreach ($interfaceNames as $interfaceName) {
                $interfaceSchemaDefinition = $fullSchemaDefinition[SchemaDefinition::ARGNAME_INTERFACES][$interfaceName];
                // If there is no definition for the interface, that means that it is not resolved by any FieldResolver
                // That's an error, so throw a helpful exception
                if (is_null($interfaceSchemaDefinition)) {
                    $translationAPI = TranslationAPIFacade::getInstance();
                    throw new Exception(sprintf(
                        $translationAPI->__('Interface \'%s\' is not resolved by any FieldResolver', 'graphql-server'),
                        $interfaceName
                    ));
                }
                $interfaceFields = array_keys($interfaceSchemaDefinition[SchemaDefinition::ARGNAME_FIELDS]);
                // Watch out (again)! An interface can itself implement interfaces,
                // and a field can be shared across them
                // (eg: field "status" for interfaces CustomPost and ContentEntry)
                // Then, check if the interface's interface also implements the field!
                // Then, do not add it yet, leave it for its implemented interface to add it
                if ($interfaceImplementedInterfaceNames = $fullSchemaDefinition[SchemaDefinition::ARGNAME_INTERFACES][$interfaceName][SchemaDefinition::ARGNAME_INTERFACES] ?? []) {
                    $interfaceImplementedInterfaceFields = [];
                    foreach ($interfaceImplementedInterfaceNames as $interfaceImplementedInterfaceName) {
                        $interfaceImplementedInterfaceFields = array_merge(
                            $interfaceImplementedInterfaceFields,
                            array_keys($fullSchemaDefinition[SchemaDefinition::ARGNAME_INTERFACES][$interfaceImplementedInterfaceName][SchemaDefinition::ARGNAME_FIELDS])
                        );
                    }
                    $interfaceFields = array_diff(
                        $interfaceFields,
                        $interfaceImplementedInterfaceFields
                    );
                }
                // Set these fields as being defined by which interface
                foreach ($interfaceFields as $interfaceField) {
                    $fieldInterfaces[$interfaceField] = $interfaceName;
                }
            }
        }
        return $fieldInterfaces;
    }
    public static function initFieldsFromPath(array &$fullSchemaDefinition, array $fieldSchemaDefinitionPath, array $interfaceNames = []): array
    {
        $addVersionToSchemaFieldDescription = Environment::addVersionToSchemaFieldDescription();
        // $fieldInterfaces = self::getFieldInterfaces($fullSchemaDefinition, $interfaceNames);
        $fieldSchemaDefinitionPointer = self::advancePointerToPath($fullSchemaDefinition, $fieldSchemaDefinitionPath);
        $fields = [];
        foreach (array_keys($fieldSchemaDefinitionPointer) as $fieldName) {
            /**
             * If an ObjectType implements an interface, and the interface
             * implements the same field, then we must return the field definition as
             * from the perspective of the interface!
             *
             * @deprecated 0.1.0 Actually, this creates another issue:
             * the type is unable to override the schema definition for a field
             * taken from an implemented interface.
             * All properties (description, isDeprecated, etc) are initially retrieved from
             * the interface, but then the type must be able to override/customize them.
             * For instance, the description can be customized to the field,
             * and the field can be deprecated just for that type (eg: Page.excerpt) and
             * not everywhere (eg: IsCustomPost.excerpt)
             */
            // if ($interfaceName = $fieldInterfaces[$fieldName] ?? null) {
            //     $targetFieldSchemaDefinitionPath = [
            //         SchemaDefinition::ARGNAME_INTERFACES,
            //         $interfaceName,
            //         SchemaDefinition::ARGNAME_FIELDS,
            //     ];
            // } else {
            $targetFieldSchemaDefinitionPath = $fieldSchemaDefinitionPath;
            // }

            /**
             * Watch out! The version comes from the field, not from the interface, so if it is defined, do not override
             * Same with the field, because in function `addVersionToSchemaFieldDescription` it may've added
             * the version at the end of the description
             */
            $customDefinition = [];
            if ($addVersionToSchemaFieldDescription) {
                if ($schemaFieldVersion = $fieldSchemaDefinitionPointer[$fieldName][SchemaDefinition::ARGNAME_VERSION] ?? null) {
                    $schemaFieldDescription = $fieldSchemaDefinitionPointer[$fieldName][SchemaDefinition::ARGNAME_DESCRIPTION];
                    $customDefinition = [
                        SchemaDefinition::ARGNAME_VERSION => $schemaFieldVersion,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $schemaFieldDescription,
                    ];
                }
            }
            $fields[] = new Field(
                $fullSchemaDefinition,
                array_merge(
                    $targetFieldSchemaDefinitionPath,
                    [
                        $fieldName
                    ]
                ),
                $customDefinition
            );
        }
        return $fields;
    }
    public static function retrieveFieldsFromPath(array &$fullSchemaDefinition, array $fieldSchemaDefinitionPath): array
    {
        $fieldSchemaDefinitionPointer = self::advancePointerToPath($fullSchemaDefinition, $fieldSchemaDefinitionPath);
        $schemaDefinitionReferenceRegistry = SchemaDefinitionReferenceRegistryFacade::getInstance();
        $fields = [];
        foreach (array_keys($fieldSchemaDefinitionPointer) as $fieldName) {
            $schemaDefinitionID = SchemaDefinitionHelpers::getID(array_merge(
                $fieldSchemaDefinitionPath,
                [
                    $fieldName
                ]
            ));
            /**
             * @var Field
             */
            $field = $schemaDefinitionReferenceRegistry->getSchemaDefinitionReference($schemaDefinitionID);
            $fields[] = $field;
        }
        return $fields;
    }
}
