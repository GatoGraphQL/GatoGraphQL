<?php

use PoP\Root\Routing\RequestNature;

class PoP_LocationPostLinksCreation_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK => [PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_LOCATIONPOSTLINK_CREATE],
            POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK => [PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::class, PoP_LocationPostLinksCreation_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_LOCATIONPOSTLINK_UPDATE],
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
		new PoP_LocationPostLinksCreation_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
