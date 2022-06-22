<?php

declare(strict_types=1);

namespace PoP\Application\ComponentFilters;

use PoP\ComponentModel\Component\Component;
use PoP\Application\ComponentProcessors\ComponentProcessorInterface;
use PoP\ComponentModel\ComponentFilters\AbstractComponentFilter;

class Lazy extends AbstractComponentFilter
{
    public function getName(): string
    {
        return 'lazy';
    }

    /**
     * Exclude if it is not lazy
     */
    public function excludeSubcomponent(Component $component, array &$props): bool
    {
        /** @var ComponentProcessorInterface */
        $processor = $this->getComponentProcessorManager()->getComponentProcessor($component);
        return !$processor->isLazyload($component, $props);
    }
}
