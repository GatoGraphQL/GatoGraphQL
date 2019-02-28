<?php
namespace PoP\Engine\Impl;

define('POP_MODULEFILTER_LAZY', 'lazy');

class ModuleFilter_Lazy extends \PoP\Engine\ModuleFilterBase
{
    public function getName()
    {
        return POP_MODULEFILTER_LAZY;
    }

    public function excludeModule($module, &$props)
    {

        // Exclude if it is not lazy
        $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();
        $processor = $moduleprocessor_manager->getProcessor($module);
        return !$processor->isLazyload($module, $props);
    }
}

/**
 * Initialization
 */
new ModuleFilter_Lazy();
