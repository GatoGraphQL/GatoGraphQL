<?php

use PoP\Root\Routing\RequestNature;

class Wassup_Share_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string,array<string,array<array<string,mixed>>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routeComponents = array(
            POP_SHARE_ROUTE_SHAREBYEMAIL => [PoP_Share_Module_Processor_Blocks::class, PoP_Share_Module_Processor_Blocks::COMPONENT_BLOCK_SHAREBYEMAIL],
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
		new Wassup_Share_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
