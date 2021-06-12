<?php

declare(strict_types=1);

namespace PoP\Application\ModuleFilters;

use PoP\ComponentModel\ModuleFilters\AbstractModuleFilter;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

class Lazy extends AbstractModuleFilter
{
    public function getName(): string
    {
        return 'lazy';
    }

    public function excludeModule(array $module, array &$props): bool
    {
        // Exclude if it is not lazy
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $processor = $moduleprocessor_manager->getProcessor($module);
        return !$processor->isLazyload($module, $props);
    }
}
