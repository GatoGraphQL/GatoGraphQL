<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

interface FilterInputContainerModuleProcessorInterface extends FilterDataModuleProcessorInterface
{
    /**
     * Provide default values for modules in the FilterInputContainer
     * @return array<string,mixed> A list of filterInputName as key, and its value
     */
    public function getFieldFilterInputDefaultValues(array $module): array;

    /**
     * Provide the names of the args which are mandatory in the FilterInput
     * @return string[]
     */
    public function getFieldFilterInputMandatoryArgs(array $module): array;

    public function getFilterInputModules(array $module): array;
}
