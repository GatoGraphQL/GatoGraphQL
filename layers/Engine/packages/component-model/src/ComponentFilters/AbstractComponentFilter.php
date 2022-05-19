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

    public function excludeSubcomponent(array $component, array &$props): bool
    {
        return false;
    }

    public function removeExcludedSubcomponents(array $component, array $subComponents): array
    {
        return $subComponents;
    }

    public function prepareForPropagation(array $component, array &$props): void
    {
    }

    public function restoreFromPropagation(array $component, array &$props): void
    {
    }
}
