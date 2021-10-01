<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

trait SchemaFilterInputModuleProcessorTrait
{
    protected function getFilterInputSchemaDefinitionResolver(array $module): DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
    {
        return $this;
    }

    public function getSchemaFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($module);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getSchemaFilterInputTypeResolver($module);
        }
        return $this->getDefaultSchemaFilterInputTypeResolver();
    }

    protected function getDefaultSchemaFilterInputTypeResolver(): InputTypeResolverInterface
    {
        $schemaDefinitionService = SchemaDefinitionServiceFacade::getInstance();
        return $schemaDefinitionService->getDefaultInputTypeResolver();
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($module);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getSchemaFilterInputDescription($module);
        }
        return null;
    }

    public function getSchemaFilterInputDeprecationDescription(array $module): ?string
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($module);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getSchemaFilterInputDeprecationDescription($module);
        }
        return null;
    }

    public function getSchemaFilterInputDefaultValue(array $module): mixed
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($module);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getSchemaFilterInputDefaultValue($module);
        }
        return null;
    }

    public function getSchemaFilterInputTypeModifiers(array $module): int
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($module);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getSchemaFilterInputTypeModifiers($module);
        }
        return 0;
    }
}
