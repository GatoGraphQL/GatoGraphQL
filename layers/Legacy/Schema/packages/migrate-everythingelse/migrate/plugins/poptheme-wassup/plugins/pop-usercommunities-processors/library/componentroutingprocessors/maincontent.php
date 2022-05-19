<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class Wassup_URE_RoleProcessors_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $routeComponents_allothers = array(
            POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS => [GD_URE_Module_Processor_ProfileBlocks::class, GD_URE_Module_Processor_ProfileBlocks::COMPONENT_BLOCK_INVITENEWMEMBERS],
            POP_USERCOMMUNITIES_ROUTE_EDITMEMBERSHIP => [GD_URE_Module_Processor_ProfileBlocks::class, GD_URE_Module_Processor_ProfileBlocks::COMPONENT_BLOCK_EDITMEMBERSHIP],
            POP_USERCOMMUNITIES_ROUTE_MYCOMMUNITIES => [GD_URE_Module_Processor_ProfileBlocks::class, GD_URE_Module_Processor_ProfileBlocks::COMPONENT_BLOCK_MYCOMMUNITIES_UPDATE],
        );
        foreach ($routeComponents_allothers as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
        }

        $default_format_users = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_USERS);
        $default_format_myusers = PoP_Application_Utils::getDefaultformatByScreen(POP_URE_SCREEN_MYUSERS);

        $routeComponents_typeahead = array(
            POP_USERCOMMUNITIES_ROUTE_COMMUNITIES => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_COMMUNITIES_TYPEAHEAD],
        );
        foreach ($routeComponents_typeahead as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_TYPEAHEAD,
                ],
            ];
            if ($default_format_users == POP_FORMAT_TYPEAHEAD) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        $routeComponents_details = array(
            POP_USERCOMMUNITIES_ROUTE_COMMUNITIES => [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_COMMUNITIES_SCROLL_DETAILS],
        );
        foreach ($routeComponents_details as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_users == POP_FORMAT_DETAILS) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_fullview = array(
            POP_USERCOMMUNITIES_ROUTE_COMMUNITIES => [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_COMMUNITIES_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_fullview as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_users == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_thumbnail = array(
            POP_USERCOMMUNITIES_ROUTE_COMMUNITIES => [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_thumbnail as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_users == POP_FORMAT_THUMBNAIL) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_list = array(
            POP_USERCOMMUNITIES_ROUTE_COMMUNITIES => [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_COMMUNITIES_SCROLL_LIST],
        );
        foreach ($routeComponents_list as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_users == POP_FORMAT_LIST) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_mycontent = array(
            POP_USERCOMMUNITIES_ROUTE_MYMEMBERS => [PoP_UserCommunities_Module_Processor_MySectionBlocks::class, PoP_UserCommunities_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYMEMBERS_TABLE_EDIT],
        );
        foreach ($routeComponents_mycontent as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_TABLE,
                ],
            ];
            if ($default_format_myusers == POP_FORMAT_TABLE) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_mycontent_previews = array(
            POP_USERCOMMUNITIES_ROUTE_MYMEMBERS => [PoP_UserCommunities_Module_Processor_MySectionBlocks::class, PoP_UserCommunities_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYMEMBERS_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_mycontent_previews as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_myusers == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        // Author route modules
        $default_format_authorusers = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORUSERS);

        $routeComponents_typeahead = array(
            POP_USERCOMMUNITIES_ROUTE_COMMUNITYPLUSMEMBERS => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD],
        );
        foreach ($routeComponents_typeahead as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_TYPEAHEAD,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_TYPEAHEAD) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }

        $routeComponents_details = array(
            POP_USERCOMMUNITIES_ROUTE_MEMBERS => [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS],
        );
        foreach ($routeComponents_details as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_DETAILS) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_fullview = array(
            POP_USERCOMMUNITIES_ROUTE_MEMBERS => [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_fullview as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_FULLVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_thumbnail = array(
            POP_USERCOMMUNITIES_ROUTE_MEMBERS => [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_thumbnail as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_THUMBNAIL) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_list = array(
            POP_USERCOMMUNITIES_ROUTE_MEMBERS => [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST],
        );
        foreach ($routeComponents_list as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_LIST) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_carousels = array(
            POP_USERCOMMUNITIES_ROUTE_MEMBERS => [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_CAROUSEL],
        );
        foreach ($routeComponents_carousels as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_CAROUSEL,
                ],
            ];
            if ($default_format_authorusers == POP_FORMAT_CAROUSEL) {
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
		new Wassup_URE_RoleProcessors_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
