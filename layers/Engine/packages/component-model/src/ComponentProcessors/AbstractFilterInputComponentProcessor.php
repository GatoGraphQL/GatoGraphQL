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

    protected function getFilterInputSchemaDefinitionResolver(array $component): FilterInputComponentProcessorInterface
    {
        return $this;
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($component);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getFilterInputTypeResolver($component);
        }
        return $this->getDefaultSchemaFilterInputTypeResolver();
    }

    protected function getDefaultSchemaFilterInputTypeResolver(): InputTypeResolverInterface
    {
        return $this->getSchemaDefinitionService()->getDefaultInputTypeResolver();
    }

    public function getFilterInputDescription(array $component): ?string
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($component);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getFilterInputDescription($component);
        }
        return null;
    }

    public function getFilterInputDefaultValue(array $component): mixed
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($component);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getFilterInputDefaultValue($component);
        }
        return null;
    }

    public function getFilterInputTypeModifiers(array $component): int
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($component);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getFilterInputTypeModifiers($component);
        }
        return SchemaTypeModifiers::NONE;
    }
}
