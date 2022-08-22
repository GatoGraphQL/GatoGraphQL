<?php

declare(strict_types=1);

namespace PoP\SPA\ComponentFilters;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentFilters\AbstractComponentFilter;
use PoP\SPA\ComponentProcessors\PageComponentProcessorInterface;

class Page extends AbstractComponentFilter
{
    public function getName(): string
    {
        return 'page';
    }

    /**
     * Exclude until reaching the pageSection
     * @param array<string,mixed> $props
     */
    public function excludeSubcomponent(Component $component, array &$props): bool
    {
        $processor = $this->getComponentProcessorManager()->getComponentProcessor($component);
        return !($processor instanceof PageComponentProcessorInterface);
    }
}
