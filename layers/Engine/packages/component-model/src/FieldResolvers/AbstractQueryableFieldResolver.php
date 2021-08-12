<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\Facades\FilterInputProcessors\FilterInputProcessorManagerFacade;
use PoP\ComponentModel\ModuleProcessors\FormComponentModuleProcessorInterface;
use PoP\ComponentModel\Resolvers\QueryableFieldResolverTrait;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

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
        return null;
    }

    protected function addFilterDataloadQueryArgs(array &$options, TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = [])
    {
        $options['filter-dataload-query-args'] = [
            'source' => $fieldArgs,
            'module' => $this->getFieldDataFilteringModule($typeResolver, $fieldName, $fieldArgs),
        ];
    }

    protected function getFilterInputName(array $filterInput): string
    {
        $filterInputProcessorManager = FilterInputProcessorManagerFacade::getInstance();
        /** @var FormComponentModuleProcessorInterface */
        $filterInputProcessor = $filterInputProcessorManager->getProcessor($filterInput);
        return $filterInputProcessor->getName($filterInput);
    }
}
