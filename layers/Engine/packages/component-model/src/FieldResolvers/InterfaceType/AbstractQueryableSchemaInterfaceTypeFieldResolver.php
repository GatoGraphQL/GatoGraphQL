<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\ComponentModel\Resolvers\QueryableFieldResolverTrait;

abstract class AbstractQueryableSchemaInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver implements QueryableInterfaceTypeFieldSchemaDefinitionResolverInterface
{
    use QueryableFieldResolverTrait;

    private ?ComponentProcessorManagerInterface $moduleProcessorManager = null;

    final public function setComponentProcessorManager(ComponentProcessorManagerInterface $moduleProcessorManager): void
    {
        $this->moduleProcessorManager = $moduleProcessorManager;
    }
    final protected function getComponentProcessorManager(): ComponentProcessorManagerInterface
    {
        return $this->moduleProcessorManager ??= $this->instanceManager->getInstance(ComponentProcessorManagerInterface::class);
    }

    public function getFieldFilterInputContainerModule(string $fieldName): ?array
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
            return $schemaDefinitionResolver->getFieldFilterInputContainerModule($fieldName);
        }
        return null;
    }

    public function getFieldArgNameTypeResolvers(string $fieldName): array
    {
        if ($filterDataloadingModule = $this->getFieldFilterInputContainerModule($fieldName)) {
            return $this->getFilterFieldArgNameTypeResolvers($filterDataloadingModule);
        }
        return parent::getFieldArgNameTypeResolvers($fieldName);
    }

    public function getFieldArgDescription(string $fieldName, string $fieldArgName): ?string
    {
        if ($filterDataloadingModule = $this->getFieldFilterInputContainerModule($fieldName)) {
            return $this->getFilterFieldArgDescription($filterDataloadingModule, $fieldArgName);
        }
        return parent::getFieldArgDescription($fieldName, $fieldArgName);
    }

    public function getFieldArgDefaultValue(string $fieldName, string $fieldArgName): mixed
    {
        if ($filterDataloadingModule = $this->getFieldFilterInputContainerModule($fieldName)) {
            return $this->getFilterFieldArgDefaultValue($filterDataloadingModule, $fieldArgName);
        }
        return parent::getFieldArgDefaultValue($fieldName, $fieldArgName);
    }

    public function getFieldArgTypeModifiers(string $fieldName, string $fieldArgName): int
    {
        if ($filterDataloadingModule = $this->getFieldFilterInputContainerModule($fieldName)) {
            return $this->getFilterFieldArgTypeModifiers($filterDataloadingModule, $fieldArgName);
        }
        return parent::getFieldArgTypeModifiers($fieldName, $fieldArgName);
    }
}
