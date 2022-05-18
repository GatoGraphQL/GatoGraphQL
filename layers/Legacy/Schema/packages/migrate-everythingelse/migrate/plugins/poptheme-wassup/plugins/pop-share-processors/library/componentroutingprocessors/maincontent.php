<?php

use PoP\Root\Routing\RequestNature;

class Wassup_Share_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_SHARE_ROUTE_SHAREBYEMAIL => [PoP_Share_Module_Processor_Blocks::class, PoP_Share_Module_Processor_Blocks::MODULE_BLOCK_SHAREBYEMAIL],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
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
