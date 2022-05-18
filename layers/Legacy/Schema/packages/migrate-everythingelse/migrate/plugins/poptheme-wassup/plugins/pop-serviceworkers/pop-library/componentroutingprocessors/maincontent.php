<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_PoPSW_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL => [GD_Core_Module_Processor_HTMLCodes::class, GD_Core_Module_Processor_HTMLCodes::MODULE_CODE_APPSHELL],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $module];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_PoPSW_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
