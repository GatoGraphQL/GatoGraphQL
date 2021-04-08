<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

trait SchemaFilterInputModuleProcessorTrait
{
    public function getSchemaFilterInputType(array $module): ?string
    {
        return null;
    }
    public function getSchemaFilterInputDescription(array $module): ?string
    {
        return null;
    }
    public function getSchemaFilterInputDeprecationDescription(array $module): ?string
    {
        return null;
    }
    public function getSchemaFilterInputMandatory(array $module): bool
    {
        return false;
    }
    public function addSchemaDefinitionForFilter(array &$schemaDefinition, array $module): void
    {
        // Override
    }
}
