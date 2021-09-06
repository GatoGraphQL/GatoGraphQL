<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\FieldResolvers\QueryableFieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\FieldResolvers\SelfQueryableFieldSchemaDefinitionResolverTrait;
use PoP\ComponentModel\ModuleProcessors\FilterDataModuleProcessorInterface;
use PoP\ComponentModel\Resolvers\QueryableFieldResolverTrait;
use PoP\ComponentModel\Resolvers\QueryableInterfaceSchemaDefinitionResolverAdapter;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

abstract class AbstractQueryableFieldResolver extends AbstractDBDataFieldResolver
{
    use QueryableFieldResolverTrait;
    use SelfQueryableFieldSchemaDefinitionResolverTrait;

    public function getSchemaFieldArgs(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): array
    {
        // Get the Schema Field Args from the FilterInput modules
        return array_merge(
            parent::getSchemaFieldArgs($relationalTypeResolver, $fieldName),
            $this->getFieldArgumentsSchemaDefinitions($relationalTypeResolver, $fieldName)
        );
    }

    protected function getFieldArgumentsSchemaDefinitions(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): array
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolverForField($relationalTypeResolver, $fieldName)) {
            /** @var QueryableFieldSchemaDefinitionResolverInterface $schemaDefinitionResolver */
            if ($filterDataloadingModule = $schemaDefinitionResolver->getFieldDataFilteringModule($relationalTypeResolver, $fieldName)) {
                $schemaFieldArgs = $this->getFilterSchemaDefinitionItems($filterDataloadingModule);
                return $this->getSchemaFieldArgsWithCustomFilterInputData(
                    $schemaFieldArgs,
                    $this->getFieldDataFilteringDefaultValues($relationalTypeResolver, $fieldName),
                    $this->getFieldDataFilteringMandatoryArgs($relationalTypeResolver, $fieldName)
                );
            }
        }

        return [];
    }

    /**
     * Provide default values for modules in the FilterInputContainer
     * @return array<string,mixed> A list of filterInputName as key, and its value
     */
    protected function getFieldDataFilteringDefaultValues(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): array
    {
        return [];
    }

    /**
     * Provide the names of the args which are mandatory in the FilterInput
     * @return string[]
     */
    protected function getFieldDataFilteringMandatoryArgs(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): array
    {
        return [];
    }

    protected function getInterfaceSchemaDefinitionResolverAdapterClass(): string
    {
        return QueryableInterfaceSchemaDefinitionResolverAdapter::class;
    }

    public function enableOrderedSchemaFieldArgs(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): bool
    {
        // If there is a filter, and it has many filterInputs, then by default we'd rather not enable ordering
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolverForField($relationalTypeResolver, $fieldName)) {
            /** @var QueryableFieldSchemaDefinitionResolverInterface $schemaDefinitionResolver */
            if ($filterDataloadingModule = $schemaDefinitionResolver->getFieldDataFilteringModule($relationalTypeResolver, $fieldName)) {
                $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
                /** @var FilterDataModuleProcessorInterface */
                $filterDataModuleProcessor = $moduleprocessor_manager->getProcessor($filterDataloadingModule);
                if (count($filterDataModuleProcessor->getDataloadQueryArgsFilteringModules($filterDataloadingModule)) > 1) {
                    return false;
                }
            }
        }
        return parent::enableOrderedSchemaFieldArgs($relationalTypeResolver, $fieldName);
    }

    /**
     * The names of the inputs supplied in the fieldArgs are not necessarily the same
     * input names expected by the function to retrieve entities in the Type API.
     *
     * For instance, input with name "searchfor" is translated as query arg "search"
     * when executing `PostTypeAPI->getPosts($query)`.
     *
     * This function transforms between the 2 states:
     *
     * - For each FilterInput defined via `getFieldDataFilteringModule`:
     * - Check if the entry with that name exists in fieldArgs, and if so:
     * - Execute `filterDataloadQueryArgs` on the FilterInput to place the value
     *   under the expected input name
     *
     * @param array<string, mixed> $fieldArgs
     * @return array<string, mixed>
     */
    protected function convertFieldArgsToFilteringQueryArgs(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): array
    {
        $filteringQueryArgs = [];
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolverForField($relationalTypeResolver, $fieldName)) {
            /** @var QueryableFieldSchemaDefinitionResolverInterface $schemaDefinitionResolver */
            if ($filterDataloadingModule = $schemaDefinitionResolver->getFieldDataFilteringModule($relationalTypeResolver, $fieldName)) {
                $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
                /** @var FilterDataModuleProcessorInterface */
                $filterDataModuleProcessor = $moduleprocessor_manager->getProcessor($filterDataloadingModule);
                $filterDataModuleProcessor->filterHeadmoduleDataloadQueryArgs($filterDataloadingModule, $filteringQueryArgs, $fieldArgs);
            }
        }
        return $filteringQueryArgs;
    }
}
