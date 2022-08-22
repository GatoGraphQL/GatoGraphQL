<?php

declare(strict_types=1);

namespace PoP\Engine\ComponentFilters;

use PoP\ComponentModel\Component\Component;
use PoP\Root\App;
use PoP\ComponentModel\ComponentFilters\AbstractComponentFilter;

class HeadComponent extends AbstractComponentFilter
{
    public function getName(): string
    {
        return 'headComponent';
    }

    /**
     * @param array<string,mixed> $props
     */
    public function excludeSubcomponent(Component $component, array &$props): bool
    {
        return App::getState('headComponent') !== $component;
    }
}
