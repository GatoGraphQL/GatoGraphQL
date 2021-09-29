<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\ModuleProcessors\FilterDataModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\FilterInputContainerModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\Resolvers\QueryableFieldResolverTrait;
use PoP\ComponentModel\Resolvers\QueryableInterfaceSchemaDefinitionResolverAdapter;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractQueryableObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver implements QueryableObjectTypeFieldSchemaDefinitionResolverInterface
{
    use QueryableFieldResolverTrait;

    protected ModuleProcessorManagerInterface $moduleProcessorManager;

    #[Required]
    public function autowireAbstractQueryableObjectTypeFieldResolver(
        ModuleProcessorManagerInterface $moduleProcessorManager,
    ): void {
        $this->moduleProcessorManager = $moduleProcessorManager;
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        /** @var QueryableObjectTypeFieldSchemaDefinitionResolverInterface */
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldFilterInputContainerModule($objectTypeResolver, $fieldName);
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
        if ($filterDataloadingModule = $this->getFieldFilterInputContainerModule($objectTypeResolver, $fieldName)) {
            $schemaFieldArgs = $this->getFilterSchemaDefinitionItems($filterDataloadingModule);
            return $this->getSchemaFieldArgsWithCustomFilterInputData(
                $schemaFieldArgs,
                $this->getFieldFilterInputDefaultValues($objectTypeResolver, $fieldName),
                $this->getFieldFilterInputMandatoryArgs($objectTypeResolver, $fieldName)
            );
        }

        return [];
    }

    /**
     * Provide default values for modules in the FilterInputContainer
     * @return array<string,mixed> A list of filterInputName as key, and its value
     */
    protected function getFieldFilterInputDefaultValues(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return [];
    }

    /**
     * Provide the names of the args which are mandatory in the FilterInput
     * @return string[]
     */
    protected function getFieldFilterInputMandatoryArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
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
        if ($filterDataloadingModule = $this->getFieldFilterInputContainerModule($objectTypeResolver, $fieldName)) {
            /** @var FilterInputContainerModuleProcessorInterface */
            $filterDataModuleProcessor = $this->moduleProcessorManager->getProcessor($filterDataloadingModule);
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
     * - For each FilterInput defined via `getFieldFilterInputContainerModule`:
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
        if ($filterDataloadingModule = $this->getFieldFilterInputContainerModule($objectTypeResolver, $fieldName)) {
            /** @var FilterDataModuleProcessorInterface */
            $filterDataModuleProcessor = $this->moduleProcessorManager->getProcessor($filterDataloadingModule);
            $filterDataModuleProcessor->filterHeadmoduleDataloadQueryArgs($filterDataloadingModule, $filteringQueryArgs, $fieldArgs);
        }
        return $filteringQueryArgs;
    }
}
