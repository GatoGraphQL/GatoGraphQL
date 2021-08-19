<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\FieldResolvers\SelfQueryableFieldSchemaDefinitionResolverTrait;
use PoP\ComponentModel\ModuleProcessors\FilterDataModuleProcessorInterface;
use PoP\ComponentModel\Resolvers\QueryableFieldResolverTrait;
use PoP\ComponentModel\Resolvers\QueryableInterfaceSchemaDefinitionResolverAdapter;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

abstract class AbstractQueryableFieldResolver extends AbstractDBDataFieldResolver
{
    use QueryableFieldResolverTrait;
    use SelfQueryableFieldSchemaDefinitionResolverTrait;

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
            return $this->getSchemaFieldArgsWithCustomFilterInputData(
                $schemaFieldArgs,
                $this->getFieldDataFilteringDefaultValues($typeResolver, $fieldName),
                $this->getFieldDataFilteringMandatoryArgs($typeResolver, $fieldName)
            );
        }

        return [];
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
     * Provide the names of the args which are mandatory in the FilterInput
     * @return string[]
     */
    protected function getFieldDataFilteringMandatoryArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        return [];
    }

    protected function getInterfaceSchemaDefinitionResolverAdapterClass(): string
    {
        return QueryableInterfaceSchemaDefinitionResolverAdapter::class;
    }

    public function enableOrderedSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        // If there is a filter, there will be many filterInputs, so by default we'd rather not enable ordering
        if ($this->getFieldDataFilteringModule($typeResolver, $fieldName) !== null) {
            return false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
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
    protected function convertFieldArgsToFilteringQueryArgs(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): array
    {
        $filteringQueryArgs = [];
        if ($filterDataloadingModule = $this->getFieldDataFilteringModule($typeResolver, $fieldName)) {
            $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
            /** @var FilterDataModuleProcessorInterface */
            $filterDataModuleProcessor = $moduleprocessor_manager->getProcessor($filterDataloadingModule);
            $filterDataModuleProcessor->filterHeadmoduleDataloadQueryArgs($filterDataloadingModule, $filteringQueryArgs, $fieldArgs);
        }
        return $filteringQueryArgs;
    }
}
