<?php

declare(strict_types=1);

namespace PoP\Engine\ComponentFilters;

use PoP\Root\App;
use PoP\ComponentModel\ComponentFilters\AbstractComponentFilter;

class MainContentComponent extends AbstractComponentFilter
{
    public function getName(): string
    {
        return 'mainContentComponent';
    }

    public function excludeSubcomponent(\PoP\ComponentModel\Component\Component $component, array &$props): bool
    {
        return App::getState('mainContentComponent') !== $component;
    }
}
