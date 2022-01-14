<?php

use PoP\Root\Routing\RouteNatures;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;

class Wassup_URE_RoleProcessors_Bootstrap_Module_MainPageSectionRouteModuleProcessor extends PoP_Module_MainPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_USERCOMMUNITIES_ROUTE_COMMUNITIES => [PoP_UserCommunities_ModuleProcessor_SectionBlocks::class, PoP_UserCommunities_ModuleProcessor_SectionBlocks::MODULE_BLOCK_TABPANEL_COMMUNITIES],
            POP_USERCOMMUNITIES_ROUTE_MYMEMBERS => [PoP_UserCommunities_ModuleProcessor_SectionBlocks::class, PoP_UserCommunities_ModuleProcessor_SectionBlocks::MODULE_BLOCK_TABPANEL_MYMEMBERS],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
                ],
            ];
        }

        // Author route modules
        $routemodules = array(
            POP_USERCOMMUNITIES_ROUTE_MEMBERS => [PoP_UserCommunities_ModuleProcessor_AuthorSectionBlocks::class, PoP_UserCommunities_ModuleProcessor_AuthorSectionBlocks::MODULE_BLOCK_TABPANEL_AUTHORCOMMUNITYMEMBERS],
        );
        foreach ($routemodules as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'themestyle' => GD_THEMESTYLE_WASSUP_EXPANSIVE,
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
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new Wassup_URE_RoleProcessors_Bootstrap_Module_MainPageSectionRouteModuleProcessor()
	);
}, 200);
