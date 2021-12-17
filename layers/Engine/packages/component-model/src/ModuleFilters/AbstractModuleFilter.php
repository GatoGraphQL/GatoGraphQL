<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleFilters;

use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\BasicService\BasicServiceTrait;

abstract class AbstractModuleFilter implements ModuleFilterInterface
{
    use BasicServiceTrait;

    private ?ModuleProcessorManagerInterface $moduleProcessorManager = null;

    final public function setModuleProcessorManager(ModuleProcessorManagerInterface $moduleProcessorManager): void
    {
        $this->moduleProcessorManager = $moduleProcessorManager;
    }
    final protected function getModuleProcessorManager(): ModuleProcessorManagerInterface
    {
        return $this->moduleProcessorManager ??= $this->instanceManager->getInstance(ModuleProcessorManagerInterface::class);
    }

    public function excludeModule(array $module, array &$props): bool
    {
        return false;
    }

    public function removeExcludedSubmodules(array $module, array $submodules): array
    {
        return $submodules;
    }

    public function prepareForPropagation(array $module, array &$props): void
    {
    }

    public function restoreFromPropagation(array $module, array &$props): void
    {
    }
}
