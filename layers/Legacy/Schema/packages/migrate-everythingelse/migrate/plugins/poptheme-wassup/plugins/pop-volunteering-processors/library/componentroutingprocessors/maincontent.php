<?php

use PoP\Root\Routing\RequestNature;

class Wassup_Volunteering_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_VOLUNTEERING_ROUTE_VOLUNTEER => [PoP_Volunteering_Module_Processor_Blocks::class, PoP_Volunteering_Module_Processor_Blocks::COMPONENT_BLOCK_VOLUNTEER],
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
		new Wassup_Volunteering_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
