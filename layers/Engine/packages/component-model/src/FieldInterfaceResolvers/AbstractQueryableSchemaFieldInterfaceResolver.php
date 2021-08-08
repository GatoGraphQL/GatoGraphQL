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
        $schemaFieldArgs = parent::getSchemaFieldArgs($fieldName);

        // Retrieve all the schema definitions for the filter inputs
        if ($filterDataloadingModule = $this->getFieldDataFilteringModule($fieldName)) {
            return array_merge(
                $schemaFieldArgs,
                $this->getFilterSchemaDefinitionItems($filterDataloadingModule)
            );
        }

        return $schemaFieldArgs;
    }
}
