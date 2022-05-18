<?php

declare(strict_types=1);

namespace PoP\Engine\ComponentFilters;

use PoP\Root\App;
use PoP\ComponentModel\ComponentFilters\AbstractComponentFilter;

class HeadModule extends AbstractComponentFilter
{
    public function getName(): string
    {
        return 'headmodule';
    }

    public function excludeModule(array $module, array &$props): bool
    {
        return App::getState('headmodule') != $module;
    }
}
