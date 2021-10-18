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

    protected function initFields(array &$fullSchemaDefinition, array $schemaDefinitionPath): void
    {
        $this->fields = [];

        // Iterate to the definition of the fields in the schema, and create an object for each of them
        $this->createFieldsFromPath(
            $fullSchemaDefinition,
            array_merge(
                $schemaDefinitionPath,
                [
                    SchemaDefinition::FIELDS,
                ]
            )
        );
        if (ComponentConfiguration::exposeGlobalFieldsInGraphQLSchema()) {
            // Global fields have already been initialized, simply get the reference to the existing objects from the registryMap
            $this->getFieldsFromPath(
                $fullSchemaDefinition,
                [
                    SchemaDefinition::GLOBAL_FIELDS,
                ]
            );
        }

        // Maybe sort fields and connections all together
        if (ComponentConfiguration::sortGraphQLSchemaAlphabetically()) {
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
