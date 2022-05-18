<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

abstract class AbstractFilterInputComponentProcessor extends AbstractFormInputComponentProcessor implements FilterInputComponentProcessorInterface
{
    private ?SchemaDefinitionServiceInterface $schemaDefinitionService = null;

    final public function setSchemaDefinitionService(SchemaDefinitionServiceInterface $schemaDefinitionService): void
    {
        $this->schemaDefinitionService = $schemaDefinitionService;
    }
    final protected function getSchemaDefinitionService(): SchemaDefinitionServiceInterface
    {
        return $this->schemaDefinitionService ??= $this->instanceManager->getInstance(SchemaDefinitionServiceInterface::class);
    }

    protected function getFilterInputSchemaDefinitionResolver(array $module): FilterInputComponentProcessorInterface
    {
        return $this;
    }

    public function getFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($module);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getFilterInputTypeResolver($module);
        }
        return $this->getDefaultSchemaFilterInputTypeResolver();
    }

    protected function getDefaultSchemaFilterInputTypeResolver(): InputTypeResolverInterface
    {
        return $this->getSchemaDefinitionService()->getDefaultInputTypeResolver();
    }

    public function getFilterInputDescription(array $module): ?string
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($module);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getFilterInputDescription($module);
        }
        return null;
    }

    public function getFilterInputDefaultValue(array $module): mixed
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($module);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getFilterInputDefaultValue($module);
        }
        return null;
    }

    public function getFilterInputTypeModifiers(array $module): int
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($module);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getFilterInputTypeModifiers($module);
        }
        return SchemaTypeModifiers::NONE;
    }
}
