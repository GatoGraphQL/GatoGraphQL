<?php

declare(strict_types=1);

namespace PoP\Engine\ModuleFilters;

use PoP\ComponentModel\ModuleFilters\AbstractModuleFilter;
use PoP\ComponentModel\State\ApplicationState;

class MainContentModule extends AbstractModuleFilter
{
    public function getName()
    {
        return 'maincontentmodule';
    }

    public function excludeModule(array $module, array &$props)
    {
        $vars = ApplicationState::getVars();
        return $vars['maincontentmodule'] != $module;
    }
}
