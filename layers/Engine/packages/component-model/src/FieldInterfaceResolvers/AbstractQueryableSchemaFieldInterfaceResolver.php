<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldInterfaceResolvers;

use PoP\ComponentModel\Resolvers\QueryableFieldResolverTrait;

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
            return $this->getFilterSchemaDefinitionItems($filterDataloadingModule);
        }

        return [];
    }
}
