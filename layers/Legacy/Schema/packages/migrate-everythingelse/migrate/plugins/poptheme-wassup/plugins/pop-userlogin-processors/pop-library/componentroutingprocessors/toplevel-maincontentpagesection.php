<?php

use PoP\Root\Routing\RequestNature;

class PoPTheme_Wassup_UserLogin_Module_ContentPageSectionTopLevelComponentRoutingProcessor extends PoP_Module_ContentPageSectionTopLevelComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routes = array(
            POP_USERLOGIN_ROUTE_LOGIN,
            POP_USERLOGIN_ROUTE_LOGOUT,
            POP_USERLOGIN_ROUTE_LOSTPWD,
            POP_USERLOGIN_ROUTE_LOSTPWDRESET,
        );
        foreach ($routes as $route) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => [PoP_Module_Processor_Offcanvas::class, PoP_Module_Processor_Offcanvas::COMPONENT_OFFCANVAS_HOVER],
                'conditions' => [
                    'target' => \PoP\ConfigurationComponentModel\Constants\Targets::MAIN,
                ],
            ];
        }

        // The routes below open in the Hole
        $routes = array(
            POP_USERLOGIN_ROUTE_LOGGEDINUSERDATA,
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
		new PoPTheme_Wassup_UserLogin_Module_ContentPageSectionTopLevelComponentRoutingProcessor()
	);
}, 200);
