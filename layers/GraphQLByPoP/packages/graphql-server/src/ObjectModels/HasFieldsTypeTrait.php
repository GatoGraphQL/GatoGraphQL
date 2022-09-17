<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\Root\App;
use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoPAPI\API\Schema\SchemaDefinition;

trait HasFieldsTypeTrait
{
    /**
     * @var Field[]
     */
    protected array $fields;

    /**
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string[] $schemaDefinitionPath
     */
    protected function initFields(array &$fullSchemaDefinition, array $schemaDefinitionPath): void
    {
        /**
         * Iterate to the definition of the fields in the schema,
         * and create an object for each of them
         */
        $this->fields = SchemaDefinitionHelpers::createFieldsFromPath(
            $fullSchemaDefinition,
            array_merge(
                $schemaDefinitionPath,
                [
                    SchemaDefinition::FIELDS,
                ]
            )
        );

        $globalFields = [];

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->exposeGlobalFieldsInGraphQLSchema()) {
            /**
             * Global fields have already been initialized,
             * simply get the reference to the existing objects
             * from the registryMap
             */
            $globalFields = SchemaDefinitionHelpers::getFieldsFromPath(
                $fullSchemaDefinition,
                [
                    SchemaDefinition::GLOBAL_FIELDS,
                ]
            );
        }

        // Maybe sort fields and connections all together
        if ($moduleConfiguration->sortGraphQLSchemaAlphabetically()) {
            if ($moduleConfiguration->sortGlobalFieldsAfterNormalFieldsInGraphQLSchema()) {
                /**
                 * Sort them separately, then merge them
                 */
                uasort($this->fields, fn (Field $a, Field $b) => $a->getName() <=> $b->getName());
                uasort($globalFields, fn (Field $a, Field $b) => $a->getName() <=> $b->getName());
                $this->fields = array_merge(
                    $this->fields,
                    $globalFields
                );
            } else {
                /**
                 * Merge them, then sort them together
                 */
                $this->fields = array_merge(
                    $this->fields,
                    $globalFields
                );
                uasort($this->fields, fn (Field $a, Field $b) => $a->getName() <=> $b->getName());
            }
        } else {
            $this->fields = array_merge(
                $this->fields,
                $globalFields
            );
        }
    }

    /**
     * @return Field[]
     */
    public function getFields(bool $includeDeprecated = false): array
    {
        return $includeDeprecated ?
            $this->fields :
            array_filter(
                $this->fields,
                function (Field $field): bool {
                    return !$field->isDeprecated();
                }
            );
    }
    /**
     * @return string[]
     */
    public function getFieldIDs(bool $includeDeprecated = false): array
    {
        return array_map(
            function (Field $field): string {
                return $field->getID();
            },
            $this->getFields($includeDeprecated)
        );
    }
}
