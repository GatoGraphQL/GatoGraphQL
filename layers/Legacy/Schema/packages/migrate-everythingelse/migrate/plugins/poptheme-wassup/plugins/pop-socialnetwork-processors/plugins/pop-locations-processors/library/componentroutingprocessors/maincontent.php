<?php

use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class Wassup_EM_SocialNetwork_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $default_format_authorusers = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORUSERS);
        $routemodules_map = array(
            POP_SOCIALNETWORK_ROUTE_FOLLOWERS => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionBlocks::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_AUTHORFOLLOWERS_SCROLLMAP],
            POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionBlocks::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_AUTHORFOLLOWINGUSERS_SCROLLMAP],
        );
        foreach ($routemodules_map as $route => $component) {
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

        // Single route modules
        $default_format_singleusers = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SINGLEUSERS);
        $routemodules_map = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionBlocks::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_SINGLERECOMMENDEDBY_SCROLLMAP],
            POP_SOCIALNETWORK_ROUTE_UPVOTEDBY => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionBlocks::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_SINGLEUPVOTEDBY_SCROLLMAP],
            POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionBlocks::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_SINGLEDOWNVOTEDBY_SCROLLMAP],
        );
        foreach ($routemodules_map as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_singleusers == POP_FORMAT_MAP) {
                $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['component' => $component];
            }
        }

        // Tag route modules
        $default_format_tagusers = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_TAGUSERS);
        $routemodules_map = array(
            POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS => [PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionBlocks::class, PoP_Locations_SocialNetwork_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_TAGSUBSCRIBERS_SCROLLMAP],
        );
        foreach ($routemodules_map as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_tagusers == POP_FORMAT_MAP) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
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
		new Wassup_EM_SocialNetwork_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
