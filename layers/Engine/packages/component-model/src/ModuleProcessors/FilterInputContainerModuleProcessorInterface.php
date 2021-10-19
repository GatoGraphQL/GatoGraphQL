<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

interface FilterInputContainerModuleProcessorInterface extends FilterDataModuleProcessorInterface
{
    public function getFieldFilterInputNameTypeResolvers(array $module): array;
    public function getFieldFilterInputDescription(array $module, string $fieldArgName): ?string;
    public function getFieldFilterInputDefaultValue(array $module, string $fieldArgName): mixed;
    public function getFieldFilterInputTypeModifiers(array $module, string $fieldArgName): int;
    public function getFilterInputModules(array $module): array;
}
