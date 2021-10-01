<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

interface FilterInputContainerModuleProcessorInterface extends FilterDataModuleProcessorInterface
{
    public function getFilterInputModules(array $module): array;
}
