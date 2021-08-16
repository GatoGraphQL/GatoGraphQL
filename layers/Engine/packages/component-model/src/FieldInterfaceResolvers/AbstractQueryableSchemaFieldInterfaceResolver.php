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
            return $this->getSchemaFieldArgsWithFilterInputDefaultValues(
                $schemaFieldArgs,
                $this->getFieldDataFilteringDefaultValues($fieldName)
            );
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
