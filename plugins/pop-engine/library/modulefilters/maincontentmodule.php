<?php
namespace PoP\Engine\Impl;

define('POP_MODULEFILTER_MAINCONTENTMODULE', 'maincontentmodule');

class ModuleFilter_MainContentModule extends \PoP\Engine\ModuleFilterBase
{
    public function getName()
    {
        return POP_MODULEFILTER_MAINCONTENTMODULE;
    }

    public function excludeModule($module, &$props)
    {
        $vars = \PoP\Engine\Engine_Vars::getVars();
        return $vars['maincontentmodule'] != $module;
    }
}

/**
 * Initialization
 */
new ModuleFilter_MainContentModule();
