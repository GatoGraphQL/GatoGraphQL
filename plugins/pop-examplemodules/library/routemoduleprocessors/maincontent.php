<?php
namespace PoP\ExampleModules;

class MainContentRouteModuleProcessor extends \PoP\Engine\MainContentRouteModuleProcessorBase
{
    public function getModulesVarsPropertiesByNature()
    {
        return array(
            POP_NATURE_HOME => [
                POP_MODULE_EXAMPLE_HOME => [[]],
            ],
            POP_NATURE_404 => [
                POP_MODULE_EXAMPLE_404 => [[]],
            ],
            POP_NATURE_TAG => [
                POP_MODULE_EXAMPLE_TAG => [[]],
            ],
            POP_NATURE_AUTHOR => [
                POP_MODULE_EXAMPLE_AUTHOR => [[]],
            ],
            POP_NATURE_SINGLE => [
                POP_MODULE_EXAMPLE_SINGLE => [[]],
            ],
            POP_NATURE_PAGE => [
                POP_MODULE_EXAMPLE_PAGE => [[]],
            ],
        );
    }
}

/**
 * Initialization
 */
new MainContentRouteModuleProcessor();
