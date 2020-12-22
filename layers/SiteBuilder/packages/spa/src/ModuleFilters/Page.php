<?php

declare(strict_types=1);

namespace PoP\SPA\ModuleFilters;

use PoP\SPA\Modules\PageInterface;
use PoP\ComponentModel\ModuleFilters\AbstractModuleFilter;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

class Page extends AbstractModuleFilter
{
    public const NAME = 'page';

    public function getName()
    {
        return self::NAME;
    }

    public function excludeModule(array $module, array &$props)
    {

        // Exclude until reaching the pageSection
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $processor = $moduleprocessor_manager->getProcessor($module);
        return !($processor instanceof PageInterface);
    }
}
