<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\FieldResolvers\QueryableFieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\ModuleProcessors\FilterDataModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\FilterInputContainerModuleProcessorInterface;
use PoP\ComponentModel\Resolvers\QueryableFieldResolverTrait;
use PoP\ComponentModel\Resolvers\QueryableInterfaceSchemaDefinitionResolverAdapter;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractQueryableFieldResolver extends AbstractDBDataFieldResolver implements QueryableFieldSchemaDefinitionResolverInterface
{
    use QueryableFieldResolverTrait;

    public function getFieldDataFilteringModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        /** @var QueryableFieldSchemaDefinitionResolverInterface */
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldDataFilteringModule($objectTypeResolver, $fieldName);
        }
        return null;
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        // Get the Schema Field Args from the FilterInput modules
        return array_merge(
            parent::getSchemaFieldArgs($objectTypeResolver, $fieldName),
            $this->getFieldArgumentsSchemaDefinitions($objectTypeResolver, $fieldName)
        );
    }

    protected function getFieldArgumentsSchemaDefinitions(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        if ($filterDataloadingModule = $this->getFieldDataFilteringModule($objectTypeResolver, $fieldName)) {
            $schemaFieldArgs = $this->getFilterSchemaDefinitionItems($filterDataloadingModule);
            return $this->getSchemaFieldArgsWithCustomFilterInputData(
                $schemaFieldArgs,
                $this->getFieldDataFilteringDefaultValues($objectTypeResolver, $fieldName),
                $this->getFieldDataFilteringMandatoryArgs($objectTypeResolver, $fieldName)
            );
        }

        return [];
    }

    /**
     * Provide default values for modules in the FilterInputContainer
     * @return array<string,mixed> A list of filterInputName as key, and its value
     */
    protected function getFieldDataFilteringDefaultValues(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return [];
    }

    /**
     * Provide the names of the args which are mandatory in the FilterInput
     * @return string[]
     */
    protected function getFieldDataFilteringMandatoryArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return [];
    }

    protected function getInterfaceSchemaDefinitionResolverAdapterClass(): string
    {
        return QueryableInterfaceSchemaDefinitionResolverAdapter::class;
    }

    public function enableOrderedSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        // If there is a filter, and it has many filterInputs, then by default we'd rather not enable ordering
        if ($filterDataloadingModule = $this->getFieldDataFilteringModule($objectTypeResolver, $fieldName)) {
            $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
            /** @var FilterInputContainerModuleProcessorInterface */
            $filterDataModuleProcessor = $moduleprocessor_manager->getProcessor($filterDataloadingModule);
            if (count($filterDataModuleProcessor->getFilterInputModules($filterDataloadingModule)) > 1) {
                return false;
            }
        }
        return parent::enableOrderedSchemaFieldArgs($objectTypeResolver, $fieldName);
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
    protected function convertFieldArgsToFilteringQueryArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): array
    {
        $filteringQueryArgs = [];
        if ($filterDataloadingModule = $this->getFieldDataFilteringModule($objectTypeResolver, $fieldName)) {
            $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
            /** @var FilterDataModuleProcessorInterface */
            $filterDataModuleProcessor = $moduleprocessor_manager->getProcessor($filterDataloadingModule);
            $filterDataModuleProcessor->filterHeadmoduleDataloadQueryArgs($filterDataloadingModule, $filteringQueryArgs, $fieldArgs);
        }
        return $filteringQueryArgs;
    }
}
