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

    protected function getFilterInputSchemaDefinitionResolver(array $componentVariation): FilterInputComponentProcessorInterface
    {
        return $this;
    }

    public function getFilterInputTypeResolver(array $componentVariation): InputTypeResolverInterface
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($componentVariation);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getFilterInputTypeResolver($componentVariation);
        }
        return $this->getDefaultSchemaFilterInputTypeResolver();
    }

    protected function getDefaultSchemaFilterInputTypeResolver(): InputTypeResolverInterface
    {
        return $this->getSchemaDefinitionService()->getDefaultInputTypeResolver();
    }

    public function getFilterInputDescription(array $componentVariation): ?string
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($componentVariation);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getFilterInputDescription($componentVariation);
        }
        return null;
    }

    public function getFilterInputDefaultValue(array $componentVariation): mixed
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($componentVariation);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getFilterInputDefaultValue($componentVariation);
        }
        return null;
    }

    public function getFilterInputTypeModifiers(array $componentVariation): int
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($componentVariation);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getFilterInputTypeModifiers($componentVariation);
        }
        return SchemaTypeModifiers::NONE;
    }
}
