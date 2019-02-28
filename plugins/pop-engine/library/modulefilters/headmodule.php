<?php
namespace PoP\Engine\Impl;

define('POP_MODULEFILTER_HEADMODULE', 'headmodule');

class ModuleFilter_HeadModule extends \PoP\Engine\ModuleFilterBase
{
    public function getName()
    {
        return POP_MODULEFILTER_HEADMODULE;
    }

    public function excludeModule($module, &$props)
    {
        $vars = \PoP\Engine\Engine_Vars::getVars();
        return $vars['headmodule'] != $module;
    }
}

/**
 * Initialization
 */
new ModuleFilter_HeadModule();
