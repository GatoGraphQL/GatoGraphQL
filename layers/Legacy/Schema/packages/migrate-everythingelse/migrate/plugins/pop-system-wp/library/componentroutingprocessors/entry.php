<?php

use PoP\Root\Routing\RequestNature;

class PoPSystem_WP_Module_EntryComponentRoutingProcessor extends \PoP\ComponentRouting\AbstractEntryComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_SYSTEMWP_ROUTE_SYSTEM_ACTIVATEPLUGINS => [PoP_SystemWP_WP_Module_Processor_SystemActions::class, PoP_SystemWP_WP_Module_Processor_SystemActions::COMPONENT_DATALOADACTION_SYSTEM_ACTIVATEPLUGINS],
        );
        foreach ($routemodules as $route => $component) {
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
    new PoPSystem_WP_Module_EntryComponentRoutingProcessor()
	);
}, 200);
