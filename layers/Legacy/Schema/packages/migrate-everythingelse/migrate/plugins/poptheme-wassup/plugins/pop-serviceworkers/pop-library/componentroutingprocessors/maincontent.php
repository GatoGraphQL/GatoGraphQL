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

        $routeComponents = array(
            POP_ENGINEWEBPLATFORM_ROUTE_APPSHELL => [GD_Core_Module_Processor_HTMLCodes::class, GD_Core_Module_Processor_HTMLCodes::COMPONENT_CODE_APPSHELL],
        );
        foreach ($routeComponents as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
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
