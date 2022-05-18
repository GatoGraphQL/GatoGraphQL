<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoPTheme_Wassup_UserCommunities_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_USERCOMMUNITIES_ROUTE_MEMBERS => [PoP_UserCommunities_Module_Processor_SidebarMultiples::class, PoP_UserCommunities_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHORCOMMUNITYMEMBERS_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'routing' => [
                        'queried-object-is-community' => true,
                    ],
                ],
            ];
        }

        $modules = array(
            POP_USERCOMMUNITIES_ROUTE_COMMUNITIES => [PoP_UserCommunities_Module_Processor_SidebarMultiples::class, PoP_UserCommunities_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_COMMUNITIES_SIDEBAR],
            POP_USERCOMMUNITIES_ROUTE_MYMEMBERS => [PoP_UserCommunities_Module_Processor_SidebarMultiples::class, PoP_UserCommunities_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_MYMEMBERS_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
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
		new PoPTheme_Wassup_UserCommunities_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
