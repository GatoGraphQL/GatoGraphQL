<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\Resolvers\QueryableFieldResolverTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

abstract class AbstractQueryableFieldResolver extends AbstractDBDataFieldResolver
{
    use QueryableFieldResolverTrait;

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        // Get the Schema Field Args from the FilterInput modules
        return array_merge(
            parent::getSchemaFieldArgs($typeResolver, $fieldName),
            $this->getFieldArgumentsSchemaDefinitions($typeResolver, $fieldName)
        );
    }

    protected function getFieldArgumentsSchemaDefinitions(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        if ($filterDataloadingModule = $this->getFieldDataFilteringModule($typeResolver, $fieldName)) {
            $schemaFieldArgs = $this->getFilterSchemaDefinitionItems($filterDataloadingModule);
            return $this->getSchemaFieldArgsWithFilterInputDefaultValues(
                $schemaFieldArgs,
                $this->getFieldDataFilteringDefaultValues($typeResolver, $fieldName)
            );
        }

        return [];
    }

    protected function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        return null;
    }

    /**
     * Provide default values for modules in the FilterInputContainer
     * @return array<string,mixed> A list of filterInputName as key, and its value
     */
    protected function getFieldDataFilteringDefaultValues(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        return [];
    }

    /**
     * @return array<string,mixed>
     */
    protected function getFilterDataloadQueryArgsOptions(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): array
    {
        return [
            'filter-dataload-query-args' => [
                'source' => $fieldArgs,
                'module' => $this->getFieldDataFilteringModule($typeResolver, $fieldName),
            ],
        ];
    }

    public function enableOrderedSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        // If there is a filter, there will be many filterInputs, so by default we'd rather not enable ordering
        if ($this->getFieldDataFilteringModule($typeResolver, $fieldName) !== null) {
            return false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }
}
