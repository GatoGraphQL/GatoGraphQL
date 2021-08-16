<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldInterfaceResolvers;

use PoP\ComponentModel\Resolvers\QueryableFieldResolverTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;

abstract class AbstractQueryableSchemaFieldInterfaceResolver extends AbstractSchemaFieldInterfaceResolver
{
    use QueryableFieldResolverTrait;

    protected function getFieldDataFilteringModule(string $fieldName): ?array
    {
        return null;
    }

    public function getSchemaFieldArgs(string $fieldName): array
    {
        // Get the Schema Field Args from the FilterInput modules
        return array_merge(
            parent::getSchemaFieldArgs($fieldName),
            $this->getFieldArgumentsSchemaDefinitions($fieldName)
        );
    }

    protected function getFieldArgumentsSchemaDefinitions(string $fieldName): array
    {
        if ($filterDataloadingModule = $this->getFieldDataFilteringModule($fieldName)) {
            $schemaFieldArgs = $this->getFilterSchemaDefinitionItems($filterDataloadingModule);
            // In the FilterInputModule we do not define default values, since different fields
            // using the same FilterInput may need a different default value.
            // Then, allow to override these values now.
            foreach ($this->getFieldDataFilteringDefaultValues($fieldName) as $filterInputName => $defaultValue) {
                foreach ($schemaFieldArgs as &$schemaFieldArg) {
                    if ($schemaFieldArg[SchemaDefinition::ARGNAME_NAME] !== $filterInputName) {
                        continue;
                    }
                    $schemaFieldArg[SchemaDefinition::ARGNAME_DEFAULT_VALUE] = $defaultValue;
                    break;
                }
            }
            return $schemaFieldArgs;
        }

        return [];
    }

    /**
     * Provide default values for modules in the FilterInputContainer
     * @return array<string,mixed> A list of filterInputName as key, and its value
     */
    protected function getFieldDataFilteringDefaultValues(string $fieldName): array
    {
        return [];
    }
}
