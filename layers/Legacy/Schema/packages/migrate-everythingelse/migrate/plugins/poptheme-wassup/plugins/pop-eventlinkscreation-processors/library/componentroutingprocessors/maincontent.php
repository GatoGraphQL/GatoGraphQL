<?php

use PoP\Root\Routing\RequestNature;

class PoP_EventLinksCreation_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK => [PoP_EventLinksCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_EventLinksCreation_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_EVENTLINK_CREATE],
            POP_EVENTLINKSCREATION_ROUTE_EDITEVENTLINK => [PoP_EventLinksCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_EventLinksCreation_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_EVENTLINK_UPDATE],
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
		new PoP_EventLinksCreation_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
