<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

interface FilterInputContainerModuleProcessorInterface extends FilterDataModuleProcessorInterface
{
    /**
     * Provide the names of the args which are mandatory in the FilterInput
     * @return string[]
     */
    public function getFieldFilterInputMandatoryArgs(array $module): array;

    public function getFilterInputModules(array $module): array;
}
