<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

interface QueryDataModuleProcessorInterface
{
    public function getDataloadQueryArgsFilteringModules(array $module): array;
    public function filterHeadmoduleDataloadQueryArgs(array $module, array &$query, array $source = null): void;
}
