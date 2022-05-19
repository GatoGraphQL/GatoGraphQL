<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_System_Module_ContentPageSectionTopLevelComponentRoutingProcessor extends PoP_Module_ContentPageSectionTopLevelComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routes = array(
            POP_SYSTEM_ROUTE_SYSTEM_BUILD,
            POP_SYSTEM_ROUTE_SYSTEM_GENERATE,
            POP_SYSTEM_ROUTE_SYSTEM_INSTALL,
        );
        foreach ($routes as $route) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => [PoP_Module_Processor_PageSectionContainers::class, PoP_Module_Processor_PageSectionContainers::COMPONENT_PAGESECTIONCONTAINER_HOLE],
                'conditions' => [
                    'target' => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
                ],
            ];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_System_Module_ContentPageSectionTopLevelComponentRoutingProcessor()
	);
}, 200);
