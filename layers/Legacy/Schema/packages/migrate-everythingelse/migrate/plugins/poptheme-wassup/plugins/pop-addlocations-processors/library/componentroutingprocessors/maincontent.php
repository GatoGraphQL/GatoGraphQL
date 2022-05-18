<?php

use PoP\Root\Routing\RequestNature;

class PoP_AddLocations_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_ADDLOCATIONS_ROUTE_ADDLOCATION => [GD_EM_Module_Processor_CreateLocationBlocks::class, GD_EM_Module_Processor_CreateLocationBlocks::COMPONENT_BLOCK_CREATELOCATION],
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
		new PoP_AddLocations_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
