<?php

declare(strict_types=1);

namespace PoP\Engine\ModuleFilters;

use PoP\Root\App;
use PoP\ComponentModel\ModuleFilters\AbstractModuleFilter;

class HeadModule extends AbstractModuleFilter
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
