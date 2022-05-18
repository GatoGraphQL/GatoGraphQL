<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleFilters;

use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractModuleFilter implements ModuleFilterInterface
{
    use BasicServiceTrait;

    private ?ComponentProcessorManagerInterface $moduleProcessorManager = null;

    final public function setComponentProcessorManager(ComponentProcessorManagerInterface $moduleProcessorManager): void
    {
        $this->moduleProcessorManager = $moduleProcessorManager;
    }
    final protected function getComponentProcessorManager(): ComponentProcessorManagerInterface
    {
        return $this->moduleProcessorManager ??= $this->instanceManager->getInstance(ComponentProcessorManagerInterface::class);
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
