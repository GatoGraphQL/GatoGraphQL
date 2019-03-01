<?php
namespace PoP\ExampleModules;

class MainContentPageModuleProcessor extends \PoP\Engine\MainContentPageModuleProcessorBase
{
    public function getNopageModulesByVarsProperties()
    {
        return array(
            POP_MODULE_EXAMPLE_HOME => array(array(
                'hierarchy' => GD_SETTINGS_HIERARCHY_HOME,
            )),
            POP_MODULE_EXAMPLE_404 => array(array(
                'hierarchy' => GD_SETTINGS_HIERARCHY_404,
            )),
            POP_MODULE_EXAMPLE_TAG => array(array(
                'hierarchy' => GD_SETTINGS_HIERARCHY_TAG,
            )),
            POP_MODULE_EXAMPLE_AUTHOR => array(array(
                'hierarchy' => GD_SETTINGS_HIERARCHY_AUTHOR,
            )),
            POP_MODULE_EXAMPLE_SINGLE => array(array(
                'hierarchy' => GD_SETTINGS_HIERARCHY_SINGLE,
            )),
            POP_MODULE_EXAMPLE_PAGE => array(array()),
        );
    }
}

/**
 * Initialization
 */
new MainContentPageModuleProcessor();
