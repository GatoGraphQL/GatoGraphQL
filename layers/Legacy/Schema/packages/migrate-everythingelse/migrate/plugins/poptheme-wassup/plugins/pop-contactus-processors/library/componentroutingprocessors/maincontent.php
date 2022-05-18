<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_ContactUs_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_CONTACTUS_ROUTE_CONTACTUS => [PoP_ContactUs_Module_Processor_Blocks::class, PoP_ContactUs_Module_Processor_Blocks::MODULE_BLOCK_CONTACTUS],
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
		new PoPTheme_Wassup_ContactUs_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
