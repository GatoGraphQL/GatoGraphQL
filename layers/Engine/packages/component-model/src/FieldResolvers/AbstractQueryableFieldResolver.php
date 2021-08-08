<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Resolvers\QueryableFieldResolverTrait;
use PoP\ComponentModel\TypeDataLoaders\TypeQueryableDataLoaderInterface;

abstract class AbstractQueryableFieldResolver extends AbstractDBDataFieldResolver
{
    use QueryableFieldResolverTrait;

    protected function getFieldArgumentsSchemaDefinitions(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): array
    {
        $schemaDefinitions = parent::getFieldArgumentsSchemaDefinitions($typeResolver, $fieldName, $fieldArgs);

        if ($filterDataloadingModule = $this->getFieldDataFilteringModule($typeResolver, $fieldName, $fieldArgs)) {
            $schemaDefinitions = array_merge(
                $schemaDefinitions,
                $this->getFilterSchemaDefinitionItems($filterDataloadingModule)
            );
        }

        return $schemaDefinitions;
    }

    protected function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        if ($fieldTypeResolverClass = $this->resolveFieldTypeResolverClass($typeResolver, $fieldName)) {
            $fieldTypeResolver = $this->instanceManager->getInstance((string)$fieldTypeResolverClass);
            $fieldTypeDataLoaderClass = $fieldTypeResolver->getTypeDataLoaderClass();
            $fieldTypeDataLoader = $this->instanceManager->getInstance((string)$fieldTypeDataLoaderClass);
            if ($fieldTypeDataLoader instanceof TypeQueryableDataLoaderInterface) {
                return $fieldTypeDataLoader->getDataFilteringModule();
            }
        }
        return null;
    }

    protected function addFilterDataloadQueryArgs(array &$options, TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = [])
    {
        $options['filter-dataload-query-args'] = [
            'source' => $fieldArgs,
            'module' => $this->getFieldDataFilteringModule($typeResolver, $fieldName, $fieldArgs),
        ];
    }
}
