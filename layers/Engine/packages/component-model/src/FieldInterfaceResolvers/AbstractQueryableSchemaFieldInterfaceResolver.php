<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldInterfaceResolvers;

use PoP\ComponentModel\Resolvers\QueryableFieldResolverTrait;

abstract class AbstractQueryableSchemaFieldInterfaceResolver extends AbstractFieldInterfaceResolver implements QueryableFieldInterfaceSchemaDefinitionResolverInterface
{
    use QueryableFieldResolverTrait;

    public function getFieldDataFilteringModule(string $fieldName): ?array
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver()) {
            // Avoid recursion when the Interface is its own DefinitionResolver
            if ($schemaDefinitionResolver === $this) {
                return null;
            }
            /** @var QueryableFieldInterfaceSchemaDefinitionResolverInterface $schemaDefinitionResolver */
            return $schemaDefinitionResolver->getFieldDataFilteringModule($fieldName);
        }
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
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver()) {
            /** @var QueryableFieldInterfaceSchemaDefinitionResolverInterface $schemaDefinitionResolver */
            if ($filterDataloadingModule = $schemaDefinitionResolver->getFieldDataFilteringModule($fieldName)) {
                $schemaFieldArgs = $this->getFilterSchemaDefinitionItems($filterDataloadingModule);
                return $this->getSchemaFieldArgsWithCustomFilterInputData(
                    $schemaFieldArgs,
                    $this->getFieldDataFilteringDefaultValues($fieldName),
                    $this->getFieldDataFilteringMandatoryArgs($fieldName)
                );
            }
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

    /**
     * Provide the names of the args which are mandatory in the FilterInput
     * @return string[]
     */
    protected function getFieldDataFilteringMandatoryArgs(string $fieldName): array
    {
        return [];
    }
}
