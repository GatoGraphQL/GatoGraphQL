<?php

declare(strict_types=1);

namespace PoP\Application\ModuleFilters;

use PoP\Application\ModuleProcessors\ModuleProcessorInterface;
use PoP\ComponentModel\ModuleFilters\AbstractModuleFilter;

class Lazy extends AbstractModuleFilter
{
    public function getName(): string
    {
        return 'lazy';
    }

    public function excludeModule(array $module, array &$props): bool
    {
        // Exclude if it is not lazy
        /** @var ModuleProcessorInterface */
        $processor = $this->moduleProcessorManager->getProcessor($module);
        return !$processor->isLazyload($module, $props);
    }
}
