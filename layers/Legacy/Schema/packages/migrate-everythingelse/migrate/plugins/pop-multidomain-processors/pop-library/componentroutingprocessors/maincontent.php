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

        $modules = array(
            POP_MULTIDOMAIN_ROUTE_EXTERNAL => [PoP_Module_Processor_MultidomainCodes::class, PoP_Module_Processor_MultidomainCodes::MODULE_CODE_EXTERNAL],
        );
        foreach ($modules as $route => $componentVariation) {
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
		new MultiDomain_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
