<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

interface DataloadQueryArgsFilterInputModuleProcessorInterface extends FormComponentModuleProcessorInterface, FilterInputModuleProcessorInterface
{
    public function getValue(array $module, ?array $source = null): mixed;
    public function isInputSetInSource(array $module, ?array $source = null): mixed;
    public function getFilterInput(array $module): ?array;
}
