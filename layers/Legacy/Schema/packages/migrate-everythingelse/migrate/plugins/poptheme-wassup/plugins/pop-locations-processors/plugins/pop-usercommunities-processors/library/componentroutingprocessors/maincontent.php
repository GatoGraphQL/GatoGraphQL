<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoP_Locations_CommonUserRoles_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string,array<string,array<array<string,mixed>>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $default_format_users = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_USERS);
        $routeComponents_map = array(
            POP_USERCOMMUNITIES_ROUTE_COMMUNITIES => [PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionBlocks::class, PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_COMMUNITIES_SCROLLMAP],
        );
        foreach ($routeComponents_map as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_users == POP_FORMAT_MAP) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        // Author route modules
        $default_format_authorusers = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORUSERS);
        $routeComponents_map = array(
            POP_USERCOMMUNITIES_ROUTE_MEMBERS => [PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionBlocks::class, PoP_UserCommunities_ComponentProcessor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLLMAP],
        );
        foreach ($routeComponents_map as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_MAP) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
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
