<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoP_Locations_CommonUserRoles_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $default_format_users = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_USERS);
        $routemodules_map = array(
            POP_USERCOMMUNITIES_ROUTE_COMMUNITIES => [PoP_UserCommunities_ModuleProcessor_CustomScrollMapSectionBlocks::class, PoP_UserCommunities_ModuleProcessor_CustomScrollMapSectionBlocks::MODULE_BLOCK_COMMUNITIES_SCROLLMAP],
        );
        foreach ($routemodules_map as $route => $module) {
            $ret[RequestNature::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_users == POP_FORMAT_MAP) {
                $ret[RequestNature::GENERIC][$route][] = ['module' => $module];
            }
        }

        // Author route modules
        $default_format_authorusers = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORUSERS);
        $routemodules_map = array(
            POP_USERCOMMUNITIES_ROUTE_MEMBERS => [PoP_UserCommunities_ModuleProcessor_CustomScrollMapSectionBlocks::class, PoP_UserCommunities_ModuleProcessor_CustomScrollMapSectionBlocks::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLLMAP],
        );
        foreach ($routemodules_map as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_MAP) {
                $ret[UserRequestNature::USER][$route][] = ['module' => $module];
            }
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoP_Locations_CommonUserRoles_Module_MainContentRouteModuleProcessor()
	);
}, 200);
