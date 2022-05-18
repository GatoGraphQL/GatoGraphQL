<?php

use PoP\Root\Routing\RequestNature;

class Wassup_Newsletter_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routeComponents = array(
            POP_NEWSLETTER_ROUTE_NEWSLETTER => [PoP_Newsletter_Module_Processor_Blocks::class, PoP_Newsletter_Module_Processor_Blocks::COMPONENT_BLOCK_NEWSLETTER],
            POP_NEWSLETTER_ROUTE_NEWSLETTERUNSUBSCRIPTION => [PoP_Newsletter_Module_Processor_Blocks::class, PoP_Newsletter_Module_Processor_Blocks::COMPONENT_BLOCK_NEWSLETTERUNSUBSCRIPTION],
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
		new Wassup_Newsletter_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
