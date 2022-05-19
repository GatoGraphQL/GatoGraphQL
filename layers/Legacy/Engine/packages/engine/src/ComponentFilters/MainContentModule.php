<?php

declare(strict_types=1);

namespace PoP\Engine\ComponentFilters;

use PoP\Root\App;
use PoP\ComponentModel\ComponentFilters\AbstractComponentFilter;

class MainContentModule extends AbstractComponentFilter
{
    public function getName(): string
    {
        return 'mainContentComponent';
    }

    public function excludeSubcomponent(array $component, array &$props): bool
    {
        return App::getState('mainContentComponent') !== $component;
    }
}
