<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait SchemaFilterInputModuleProcessorTrait
{
    protected SchemaDefinitionServiceInterface $schemaDefinitionService;

    #[Required]
    public function autowireSchemaFilterInputModuleProcessorTrait(
        SchemaDefinitionServiceInterface $schemaDefinitionService,
    ): void {
        $this->schemaDefinitionService = $schemaDefinitionService;
    }

    protected function getFilterInputSchemaDefinitionResolver(array $module): DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
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
        return $this->schemaDefinitionService->getDefaultInputTypeResolver();
    }

    public function getFilterInputDescription(array $module): ?string
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($module);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getFilterInputDescription($module);
        }
        return null;
    }

    public function getFilterInputDeprecationDescription(array $module): ?string
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($module);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getFilterInputDeprecationDescription($module);
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
