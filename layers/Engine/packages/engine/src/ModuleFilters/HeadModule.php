<?php

declare(strict_types=1);

namespace PoP\Engine\ModuleFilters;

use PoP\ComponentModel\ModuleFilters\AbstractModuleFilter;
use PoP\ComponentModel\State\ApplicationState;

class HeadModule extends AbstractModuleFilter
{
    public const URLPARAM_HEADMODULE = 'headmodule';

    public function getName()
    {
        return 'headmodule';
    }

    public function excludeModule(array $module, array &$props)
    {
        $vars = ApplicationState::getVars();
        return $vars['headmodule'] != $module;
    }
}
