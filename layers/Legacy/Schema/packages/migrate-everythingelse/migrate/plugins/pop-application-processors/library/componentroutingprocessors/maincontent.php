<?php

use PoP\Root\Routing\RequestNature;

class Domain_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $componentVariations = array(
            POP_DOMAIN_ROUTE_LOADERS_INITIALIZEDOMAIN => [PoP_MultidomainProcessors_Module_Processor_Dataloads::class, PoP_MultidomainProcessors_Module_Processor_Dataloads::MODULE_DATALOAD_INITIALIZEDOMAIN],
        );
        foreach ($componentVariations as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new Domain_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
