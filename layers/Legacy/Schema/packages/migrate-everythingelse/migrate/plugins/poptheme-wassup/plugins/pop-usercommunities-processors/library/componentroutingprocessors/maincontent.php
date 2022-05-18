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

        $routemodules_allothers = array(
            POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS => [GD_URE_Module_Processor_ProfileBlocks::class, GD_URE_Module_Processor_ProfileBlocks::COMPONENT_BLOCK_INVITENEWMEMBERS],
            POP_USERCOMMUNITIES_ROUTE_EDITMEMBERSHIP => [GD_URE_Module_Processor_ProfileBlocks::class, GD_URE_Module_Processor_ProfileBlocks::COMPONENT_BLOCK_EDITMEMBERSHIP],
            POP_USERCOMMUNITIES_ROUTE_MYCOMMUNITIES => [GD_URE_Module_Processor_ProfileBlocks::class, GD_URE_Module_Processor_ProfileBlocks::COMPONENT_BLOCK_MYCOMMUNITIES_UPDATE],
        );
        foreach ($routemodules_allothers as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
        }

        $default_format_users = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_USERS);
        $default_format_myusers = PoP_Application_Utils::getDefaultformatByScreen(POP_URE_SCREEN_MYUSERS);

        $routemodules_typeahead = array(
            POP_USERCOMMUNITIES_ROUTE_COMMUNITIES => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_COMMUNITIES_TYPEAHEAD],
        );
        foreach ($routemodules_typeahead as $route => $component) {
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

        $routemodules_details = array(
            POP_USERCOMMUNITIES_ROUTE_COMMUNITIES => [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_COMMUNITIES_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $component) {
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
        $routemodules_fullview = array(
            POP_USERCOMMUNITIES_ROUTE_COMMUNITIES => [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_COMMUNITIES_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $component) {
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
        $routemodules_thumbnail = array(
            POP_USERCOMMUNITIES_ROUTE_COMMUNITIES => [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_COMMUNITIES_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $component) {
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
        $routemodules_list = array(
            POP_USERCOMMUNITIES_ROUTE_COMMUNITIES => [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_COMMUNITIES_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $component) {
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
        $routemodules_mycontent = array(
            POP_USERCOMMUNITIES_ROUTE_MYMEMBERS => [PoP_UserCommunities_Module_Processor_MySectionBlocks::class, PoP_UserCommunities_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYMEMBERS_TABLE_EDIT],
        );
        foreach ($routemodules_mycontent as $route => $component) {
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
        $routemodules_mycontent_previews = array(
            POP_USERCOMMUNITIES_ROUTE_MYMEMBERS => [PoP_UserCommunities_Module_Processor_MySectionBlocks::class, PoP_UserCommunities_Module_Processor_MySectionBlocks::COMPONENT_BLOCK_MYMEMBERS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_mycontent_previews as $route => $component) {
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

        $routemodules_typeahead = array(
            POP_USERCOMMUNITIES_ROUTE_COMMUNITYPLUSMEMBERS => [PoP_UserCommunities_Module_Processor_CustomSectionDataloads::class, PoP_UserCommunities_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_AUTHORPLUSCOMMUNITYMEMBERS_TYPEAHEAD],
        );
        foreach ($routemodules_typeahead as $route => $component) {
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

        $routemodules_details = array(
            POP_USERCOMMUNITIES_ROUTE_MEMBERS => [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $component) {
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
        $routemodules_fullview = array(
            POP_USERCOMMUNITIES_ROUTE_MEMBERS => [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $component) {
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
        $routemodules_thumbnail = array(
            POP_USERCOMMUNITIES_ROUTE_MEMBERS => [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $component) {
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
        $routemodules_list = array(
            POP_USERCOMMUNITIES_ROUTE_MEMBERS => [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $component) {
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
        $routemodules_carousels = array(
            POP_USERCOMMUNITIES_ROUTE_MEMBERS => [PoP_UserCommunities_Module_Processor_CustomSectionBlocks::class, PoP_UserCommunities_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORCOMMUNITYMEMBERS_CAROUSEL],
        );
        foreach ($routemodules_carousels as $route => $component) {
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
