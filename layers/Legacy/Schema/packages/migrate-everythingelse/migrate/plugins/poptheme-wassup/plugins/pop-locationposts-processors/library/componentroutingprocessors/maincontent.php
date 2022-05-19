<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class PoP_CommonPages_EM_Module_MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTION);

        $routeComponents_typeahead = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::COMPONENT_DATALOAD_LOCATIONPOSTS_TYPEAHEAD],
        );
        foreach ($routeComponents_typeahead as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_TYPEAHEAD,
                ],
            ];
            if ($default_format_section == POP_FORMAT_TYPEAHEAD) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        $routeComponents_navigator = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_LOCATIONPOSTS_SCROLL_NAVIGATOR],
        );
        foreach ($routeComponents_navigator as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_NAVIGATOR,
                ],
            ];
            if ($default_format_section == POP_FORMAT_NAVIGATOR) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }

            // Navigator special case: use the NAVIGATOR module when the target is the navigator
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'target' => POP_TARGET_NAVIGATOR,
                ],
            ];
        }

        $routeComponents_addons = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_LOCATIONPOSTS_SCROLL_ADDONS],
        );
        foreach ($routeComponents_addons as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_ADDONS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_ADDONS) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        $routeComponents_details = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_LOCATIONPOSTS_SCROLL_DETAILS],
        );
        foreach ($routeComponents_details as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        $routeComponents_simpleview = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_LOCATIONPOSTS_SCROLL_SIMPLEVIEW],
        );
        foreach ($routeComponents_simpleview as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_fullview = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_LOCATIONPOSTS_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_fullview as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_thumbnail = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_LOCATIONPOSTS_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_thumbnail as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_list = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_LOCATIONPOSTS_SCROLL_LIST],
        );
        foreach ($routeComponents_list as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_map = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::class, GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_LOCATIONPOSTS_SCROLLMAP],
        );
        foreach ($routeComponents_map as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_MAP) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }
        $routeComponents_horizontalmap = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::class, GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP],
        );
        foreach ($routeComponents_horizontalmap as $route => $component) {
            $ret[RequestNature::GENERIC][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_HORIZONTALMAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_HORIZONTALMAP) {
                $ret[RequestNature::GENERIC][$route][] = ['component' => $component];
            }
        }

        // Author route modules
        $default_format_authorsection = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORSECTION);

        $routeComponents_details = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_DETAILS],
        );
        foreach ($routeComponents_details as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_DETAILS) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_simpleview = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
        );
        foreach ($routeComponents_simpleview as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_SIMPLEVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_fullview = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_fullview as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_FULLVIEW) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_thumbnail = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_thumbnail as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_THUMBNAIL) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_list = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_LIST],
        );
        foreach ($routeComponents_list as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_LIST) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_map = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::class, GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_SCROLLMAP],
        );
        foreach ($routeComponents_map as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_MAP) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }
        $routeComponents_horizontalmap = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::class, GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP],
        );
        foreach ($routeComponents_horizontalmap as $route => $component) {
            $ret[UserRequestNature::USER][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_HORIZONTALMAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_HORIZONTALMAP) {
                $ret[UserRequestNature::USER][$route][] = ['component' => $component];
            }
        }

        // Tag route modules
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_TAGSECTION);

        $routeComponents_details = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGLOCATIONPOSTS_SCROLL_DETAILS],
        );
        foreach ($routeComponents_details as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routeComponents_simpleview = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
        );
        foreach ($routeComponents_simpleview as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routeComponents_fullview = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGLOCATIONPOSTS_SCROLL_FULLVIEW],
        );
        foreach ($routeComponents_fullview as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routeComponents_thumbnail = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL],
        );
        foreach ($routeComponents_thumbnail as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routeComponents_list = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_TAGLOCATIONPOSTS_SCROLL_LIST],
        );
        foreach ($routeComponents_list as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routeComponents_map = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::class, GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP],
        );
        foreach ($routeComponents_map as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_MAP) {
                $ret[TagRequestNature::TAG][$route][] = ['component' => $component];
            }
        }
        $routeComponents_horizontalmap = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::class, GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP],
        );
        foreach ($routeComponents_horizontalmap as $route => $component) {
            $ret[TagRequestNature::TAG][$route][] = [
                'component' => $component,
                'conditions' => [
                    'format' => POP_FORMAT_HORIZONTALMAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_HORIZONTALMAP) {
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
		new PoP_CommonPages_EM_Module_MainContentComponentRoutingProcessor()
	);
}, 200);
