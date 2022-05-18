<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_UserPlatform_Module_ContentPageSectionTopLevelComponentRoutingProcessor extends PoP_Module_ContentPageSectionTopLevelComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        // The routes below open in the Hover
        $routes = array(
            POP_USERPLATFORM_ROUTE_SETTINGS,
        );
        foreach ($routes as $route) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::COMPONENT_OFFCANVAS_HOVER],
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
		new PoPTheme_Wassup_UserPlatform_Module_ContentPageSectionTopLevelComponentRoutingProcessor()
	);
}, 200);
