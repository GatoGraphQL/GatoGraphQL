<?php

use PoP\Root\Routing\RequestNature;

class MultiDomain_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $components = array(
            POP_MULTIDOMAIN_ROUTE_EXTERNAL => [PoP_Module_Processor_MultidomainCodes::class, PoP_Module_Processor_MultidomainCodes::COMPONENT_CODE_EXTERNAL],
        );
        foreach ($components as $route => $component) {
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
		new MultiDomain_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
