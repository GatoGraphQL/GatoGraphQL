<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\ComponentModel\Resolvers\QueryableFieldResolverTrait;

abstract class AbstractQueryableSchemaInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver implements QueryableInterfaceTypeFieldSchemaDefinitionResolverInterface
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

    public function getFieldFilterInputContainerComponent(string $fieldName): ?Component
    {
        /**
         * An interface may implement another interface which is not Queryable
         */
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if (!($schemaDefinitionResolver instanceof QueryableInterfaceTypeFieldSchemaDefinitionResolverInterface)) {
            return null;
        }

        /** @var QueryableInterfaceTypeFieldSchemaDefinitionResolverInterface $schemaDefinitionResolver */
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldFilterInputContainerComponent($fieldName);
        }
        return null;
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(string $fieldName): array
    {
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($fieldName)) {
            return $this->getFilterFieldArgNameTypeResolvers($filterDataloadingComponent);
        }
        return parent::getFieldArgNameTypeResolvers($fieldName);
    }

    public function getFieldArgDescription(string $fieldName, string $fieldArgName): ?string
    {
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($fieldName)) {
            return $this->getFilterFieldArgDescription($filterDataloadingComponent, $fieldArgName);
        }
        return parent::getFieldArgDescription($fieldName, $fieldArgName);
    }

    public function getFieldArgDefaultValue(string $fieldName, string $fieldArgName): mixed
    {
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($fieldName)) {
            return $this->getFilterFieldArgDefaultValue($filterDataloadingComponent, $fieldArgName);
        }
        return parent::getFieldArgDefaultValue($fieldName, $fieldArgName);
    }

    public function getFieldArgTypeModifiers(string $fieldName, string $fieldArgName): int
    {
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($fieldName)) {
            return $this->getFilterFieldArgTypeModifiers($filterDataloadingComponent, $fieldArgName);
        }
        return parent::getFieldArgTypeModifiers($fieldName, $fieldArgName);
    }
}
