<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentFilters;

use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractComponentFilter implements ComponentFilterInterface
{
    use BasicServiceTrait;

    private ?ComponentProcessorManagerInterface $componentProcessorManager = null;

    final public function setComponentProcessorManager(ComponentProcessorManagerInterface $componentProcessorManager): void
    {
        $this->componentProcessorManager = $componentProcessorManager;
    }
    final protected function getComponentProcessorManager(): ComponentProcessorManagerInterface
    {
        return $this->componentProcessorManager ??= $this->instanceManager->getInstance(ComponentProcessorManagerInterface::class);
    }

    public function excludeModule(array $componentVariation, array &$props): bool
    {
        return false;
    }

    public function removeExcludedSubmodules(array $componentVariation, array $submodules): array
    {
        return $submodules;
    }

    public function prepareForPropagation(array $componentVariation, array &$props): void
    {
    }

    public function restoreFromPropagation(array $componentVariation, array &$props): void
    {
    }
}
