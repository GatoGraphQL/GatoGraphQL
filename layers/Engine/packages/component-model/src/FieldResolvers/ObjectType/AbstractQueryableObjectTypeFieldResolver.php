<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\ComponentModel\ComponentProcessors\FilterDataComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\FilterInputContainerComponentProcessorInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Resolvers\InterfaceSchemaDefinitionResolverAdapter;
use PoP\ComponentModel\Resolvers\QueryableFieldResolverTrait;
use PoP\ComponentModel\Resolvers\QueryableInterfaceSchemaDefinitionResolverAdapter;
use PoP\ComponentModel\TypeResolvers\InputObjectType\QueryableInputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException;

abstract class AbstractQueryableObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver implements QueryableObjectTypeFieldSchemaDefinitionResolverInterface
{
    use QueryableFieldResolverTrait;

    private ?ComponentProcessorManagerInterface $componentProcessorManager = null;

    final protected function getComponentProcessorManager(): ComponentProcessorManagerInterface
    {
        if ($this->componentProcessorManager === null) {
            /** @var ComponentProcessorManagerInterface */
            $componentProcessorManager = $this->instanceManager->getInstance(ComponentProcessorManagerInterface::class);
            $this->componentProcessorManager = $componentProcessorManager;
        }
        return $this->componentProcessorManager;
    }

    public function getFieldFilterInputContainerComponent(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?Component
    {
        /** @var QueryableObjectTypeFieldSchemaDefinitionResolverInterface */
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName);
        }
        return null;
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName)) {
            return $this->getFilterFieldArgNameTypeResolvers($filterDataloadingComponent);
        }
        return parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName)) {
            return $this->getFilterFieldArgDescription($filterDataloadingComponent, $fieldArgName);
        }
        return parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName);
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName)) {
            return $this->getFilterFieldArgDefaultValue($filterDataloadingComponent, $fieldArgName);
        }
        return parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName)) {
            return $this->getFilterFieldArgTypeModifiers($filterDataloadingComponent, $fieldArgName);
        }
        return parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
    }

    /**
     * @return class-string<InterfaceSchemaDefinitionResolverAdapter>
     */
    protected function getInterfaceSchemaDefinitionResolverAdapterClass(): string
    {
        return QueryableInterfaceSchemaDefinitionResolverAdapter::class;
    }

    public function enableOrderedSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        // If there is a filter, and it has many filterInputs, then by default we'd rather not enable ordering
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName)) {
            /** @var FilterInputContainerComponentProcessorInterface */
            $filterDataComponentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($filterDataloadingComponent);
            if (count($filterDataComponentProcessor->getFilterInputComponents($filterDataloadingComponent)) > 1) {
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
     * - For each FilterInput defined via `getFieldFilterInputContainerComponent`:
     *   - Check if the entry with that name exists in fieldArgs, and if so:
     *     - Execute `filterDataloadQueryArgs` on the FilterInput to place the value
     *       under the expected input name
     *
     * @return array<string,mixed>
     * @throws AbstractValueResolutionPromiseException
     */
    protected function convertFieldArgsToFilteringQueryArgs(ObjectTypeResolverInterface $objectTypeResolver, FieldDataAccessorInterface $fieldDataAccessor): array
    {
        $filteringQueryArgs = [];
        $fieldName = $fieldDataAccessor->getFieldName();
        $fieldArgs = $fieldDataAccessor->getFieldArgs();
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName)) {
            /** @var FilterDataComponentProcessorInterface */
            $filterDataComponentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($filterDataloadingComponent);
            $filterDataComponentProcessor->filterHeadcomponentDataloadQueryArgs($filterDataloadingComponent, $filteringQueryArgs, $fieldArgs);
        }
        // InputObjects can also provide filtering query values
        $consolidatedFieldArgNameTypeResolvers = $this->getConsolidatedFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        foreach ($fieldArgs as $argName => $argValue) {
            $fieldArgTypeResolver = $consolidatedFieldArgNameTypeResolvers[$argName];
            if (!($fieldArgTypeResolver instanceof QueryableInputObjectTypeResolverInterface)) {
                continue;
            }
            $fieldArgTypeResolver->integrateInputValueToFilteringQueryArgs($filteringQueryArgs, $argValue);
        }
        return $filteringQueryArgs;
    }
}
