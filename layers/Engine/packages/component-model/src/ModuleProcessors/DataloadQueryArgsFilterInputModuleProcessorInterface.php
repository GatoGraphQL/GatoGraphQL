<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

interface DataloadQueryArgsFilterInputModuleProcessorInterface
{
    public function getValue(array $module, ?array $source = null);
    public function getFilterInput(array $module): ?array;
    public function getFilterInputSchemaDefinitionItems(array $module): array;
    public function getFilterInputSchemaDefinitionResolver(array $module): ?DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
}
