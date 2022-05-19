<?php

declare(strict_types=1);

namespace PoP\Engine\ComponentFilters;

use PoP\Root\App;
use PoP\ComponentModel\ComponentFilters\AbstractComponentFilter;

class HeadModule extends AbstractComponentFilter
{
    public function getName(): string
    {
        return 'headComponent';
    }

    public function excludeSubcomponent(array $component, array &$props): bool
    {
        return App::getState('headComponent') !== $component;
    }
}
