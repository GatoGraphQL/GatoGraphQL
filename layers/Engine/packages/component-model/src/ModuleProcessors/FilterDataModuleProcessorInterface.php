<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

interface FilterDataModuleProcessorInterface
{
    public function getDataloadQueryArgsFilteringModules(array $module): array;
    public function getActiveDataloadQueryArgsFilteringModules(array $module, array $source = null): array;
    public function filterHeadmoduleDataloadQueryArgs(array $module, array &$query, array $source = null): void;
}
