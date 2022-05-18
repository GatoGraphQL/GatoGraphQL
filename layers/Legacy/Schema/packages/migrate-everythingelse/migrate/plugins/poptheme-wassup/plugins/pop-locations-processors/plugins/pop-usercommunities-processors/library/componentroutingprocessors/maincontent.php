<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoP_Locations_CommonUserRoles_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $default_format_users = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_USERS);
        $routemodules_map = array(
            POP_USERCOMMUNITIES_ROUTE_COMMUNITIES => [PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionBlocks::class, PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionBlocks::MODULE_BLOCK_COMMUNITIES_SCROLLMAP],
        );
        foreach ($routemodules_map as $route => $componentVariation) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_users == POP_FORMAT_MAP) {
                $ret[RequestNature::GENERIC][$route][] = ['component-variation' => $componentVariation];
            }
        }

        // Author route modules
        $default_format_authorusers = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORUSERS);
        $routemodules_map = array(
            POP_USERCOMMUNITIES_ROUTE_MEMBERS => [PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionBlocks::class, PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionBlocks::MODULE_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLLMAP],
        );
        foreach ($routemodules_map as $route => $componentVariation) {
            $ret[UserRequestNature::USER][$route][] = [
                'component-variation' => $componentVariation,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_MAP) {
                $ret[UserRequestNature::USER][$route][] = ['component-variation' => $componentVariation];
            }
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoP_Locations_CommonUserRoles_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
