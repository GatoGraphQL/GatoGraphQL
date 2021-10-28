<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Facades\Schema\FieldGraphQLSchemaDefinitionHelperFacade;
use PoP\API\Schema\SchemaDefinition;

trait HasFieldsTypeTrait
{
    /** @var array<Field|WrappingTypeInterface> */
    protected array $fields;

    protected function initFields(array &$fullSchemaDefinition, array $schemaDefinitionPath): void
    {
        $fieldGraphQLSchemaDefinitionHelper = FieldGraphQLSchemaDefinitionHelperFacade::getInstance();
        // Iterate to the definition of the fields in the schema, and create an object for each of them
        $this->fields = $fieldGraphQLSchemaDefinitionHelper->createFieldsFromPath(
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
            $this->fields = array_merge(
                $this->fields,
                $fieldGraphQLSchemaDefinitionHelper->getFieldsFromPath(
                    $fullSchemaDefinition,
                    [
                        SchemaDefinition::GLOBAL_FIELDS,
                    ]
                )
            );
        }

        // Maybe sort fields and connections all together
        if (ComponentConfiguration::sortGraphQLSchemaAlphabetically()) {
            uasort($this->fields, function (Field $a, Field $b): int {
                return $a->getName() <=> $b->getName();
            });
        }
    }

    public function getFields(bool $includeDeprecated = false): array
    {
        return $includeDeprecated ?
            $this->fields :
            array_filter(
                $this->fields,
                function (Field | WrappingTypeInterface $fieldOrWrappingType) {
                    while ($fieldOrWrappingType instanceof WrappingTypeInterface) {
                        $fieldOrWrappingType = $fieldOrWrappingType->getWrappedType();
                    }
                    /** @var Field */
                    $field = $fieldOrWrappingType;
                    return !$field->isDeprecated();
                }
            );
    }
    public function getFieldIDs(bool $includeDeprecated = false): array
    {
        $ids = [];
        foreach ($this->getFields($includeDeprecated) as $fieldOrWrappingType) {
            $ids[] = $fieldOrWrappingType->getID();
        }
        return $ids;
    }
}
