<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoP\API\Schema\SchemaDefinition;

trait HasFieldsTypeTrait
{
    /**
     * @var Field[]
     */
    protected array $fields;

    protected function initFields(array &$fullSchemaDefinition, array $schemaDefinitionPath, bool $includeConnections): void
    {
        $this->fields = [];

        // Iterate to the definition of the fields in the schema, and create an object for each of them
        // Print connections and then fields, it looks better in the Interactive Schema
        // 1. Connections under this type
        if ($includeConnections) {
            $this->createFieldsFromPath(
                $fullSchemaDefinition,
                array_merge(
                    $schemaDefinitionPath,
                    [
                        SchemaDefinition::CONNECTIONS,
                    ]
                )
            );
        }
        // 2. Fields under this type
        $this->createFieldsFromPath(
            $fullSchemaDefinition,
            array_merge(
                $schemaDefinitionPath,
                [
                    SchemaDefinition::FIELDS,
                ]
            )
        );
        if (ComponentConfiguration::addGlobalFieldsToSchema()) {
            // Global fields and connections have already been initialized, simply get the reference to the existing objects from the registryMap
            // 1. Global fields
            $this->getFieldsFromPath(
                $fullSchemaDefinition,
                [
                    SchemaDefinition::GLOBAL_FIELDS,
                ]
            );
            // 2. Global connections
            if ($includeConnections) {
                $this->getFieldsFromPath(
                    $fullSchemaDefinition,
                    [
                        SchemaDefinition::GLOBAL_CONNECTIONS,
                    ]
                );
            }
        }

        // Maybe sort fields and connections all together
        if (ComponentConfiguration::sortSchemaAlphabetically()) {
            uasort($this->fields, function (Field $a, Field $b): int {
                return $a->getName() <=> $b->getName();
            });
        }
    }
    protected function createFieldsFromPath(array &$fullSchemaDefinition, array $fieldSchemaDefinitionPath): void
    {
        $this->fields = array_merge(
            $this->fields,
            SchemaDefinitionHelpers::createFieldsFromPath($fullSchemaDefinition, $fieldSchemaDefinitionPath)
        );
    }
    protected function getFieldsFromPath(array &$fullSchemaDefinition, array $fieldSchemaDefinitionPath): void
    {
        $this->fields = array_merge(
            $this->fields,
            SchemaDefinitionHelpers::getFieldsFromPath($fullSchemaDefinition, $fieldSchemaDefinitionPath)
        );
    }

    public function getFields(bool $includeDeprecated = false): array
    {
        return $includeDeprecated ?
            $this->fields :
            array_filter(
                $this->fields,
                function (Field $field) {
                    return !$field->isDeprecated();
                }
            );
    }
    public function getFieldIDs(bool $includeDeprecated = false): array
    {
        return array_map(
            function (Field $field) {
                return $field->getID();
            },
            $this->getFields($includeDeprecated)
        );
    }
}
