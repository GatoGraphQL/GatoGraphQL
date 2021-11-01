<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

interface DataloadQueryArgsFilterInputModuleProcessorInterface extends FilterInputModuleProcessorInterface
{
    public function getFilterInput(array $module): ?array;
}
