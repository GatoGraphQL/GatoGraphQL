<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

trait DataloadQueryArgsSchemaFilterInputModuleProcessorTrait
{
    use FilterInputModuleProcessorTrait, SchemaFilterInputModuleProcessorTrait;

    public function getFilterInputSchemaDefinitionResolver(array $module): ?DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
    {
        return $this;
    }
}
