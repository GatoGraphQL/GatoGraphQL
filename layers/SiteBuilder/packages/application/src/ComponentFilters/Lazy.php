<?php

declare(strict_types=1);

namespace PoP\Application\ComponentFilters;

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
    public function excludeModule(array $componentVariation, array &$props): bool
    {
        /** @var ComponentProcessorInterface */
        $processor = $this->getComponentProcessorManager()->getProcessor($componentVariation);
        return !$processor->isLazyload($componentVariation, $props);
    }
}
