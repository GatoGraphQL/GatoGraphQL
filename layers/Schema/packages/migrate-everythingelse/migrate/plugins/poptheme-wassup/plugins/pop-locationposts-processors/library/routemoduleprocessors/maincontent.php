<?php

use PoP\Routing\RouteNatures;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;

class PoP_CommonPages_EM_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_SECTION);

        $routemodules_typeahead = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionDataloads::class, PoP_LocationPosts_Module_Processor_CustomSectionDataloads::MODULE_DATALOAD_LOCATIONPOSTS_TYPEAHEAD],
        );
        foreach ($routemodules_typeahead as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_TYPEAHEAD,
                ],
            ];
            if ($default_format_section == POP_FORMAT_TYPEAHEAD) {
                $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
            }
        }

        $routemodules_navigator = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_NAVIGATOR],
        );
        foreach ($routemodules_navigator as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_NAVIGATOR,
                ],
            ];
            if ($default_format_section == POP_FORMAT_NAVIGATOR) {
                $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
            }

            // Navigator special case: use the NAVIGATOR module when the target is the navigator
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'target' => POP_TARGET_NAVIGATOR,
                ],
            ];
        }

        $routemodules_addons = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_ADDONS],
        );
        foreach ($routemodules_addons as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_ADDONS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_ADDONS) {
                $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
            }
        }

        $routemodules_details = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
            }
        }

        $routemodules_simpleview = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
            }
        }
        $routemodules_fullview = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
            }
        }
        $routemodules_thumbnail = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
            }
        }
        $routemodules_list = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_LOCATIONPOSTS_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
            }
        }
        $routemodules_map = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::class, GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_LOCATIONPOSTS_SCROLLMAP],
        );
        foreach ($routemodules_map as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_MAP) {
                $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
            }
        }
        $routemodules_horizontalmap = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::class, GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_LOCATIONPOSTS_HORIZONTALSCROLLMAP],
        );
        foreach ($routemodules_horizontalmap as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_HORIZONTALMAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_HORIZONTALMAP) {
                $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
            }
        }

        // Author route modules
        $default_format_authorsection = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_AUTHORSECTION);

        $routemodules_details = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_DETAILS) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_simpleview = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_SIMPLEVIEW) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_fullview = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_FULLVIEW) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_thumbnail = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_THUMBNAIL) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_list = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_authorsection == POP_FORMAT_LIST) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_map = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::class, GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_AUTHORLOCATIONPOSTS_SCROLLMAP],
        );
        foreach ($routemodules_map as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_MAP) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }
        $routemodules_horizontalmap = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::class, GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_AUTHORLOCATIONPOSTS_HORIZONTALSCROLLMAP],
        );
        foreach ($routemodules_horizontalmap as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_HORIZONTALMAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_HORIZONTALMAP) {
                $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
            }
        }

        // Tag route modules
        $default_format_section = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_TAGSECTION);

        $routemodules_details = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_DETAILS],
        );
        foreach ($routemodules_details as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_DETAILS,
                ],
            ];
            if ($default_format_section == POP_FORMAT_DETAILS) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_simpleview = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_SIMPLEVIEW],
        );
        foreach ($routemodules_simpleview as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_SIMPLEVIEW) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_fullview = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_FULLVIEW],
        );
        foreach ($routemodules_fullview as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_section == POP_FORMAT_FULLVIEW) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_thumbnail = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_THUMBNAIL],
        );
        foreach ($routemodules_thumbnail as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_THUMBNAIL,
                ],
            ];
            if ($default_format_section == POP_FORMAT_THUMBNAIL) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_list = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoP_LocationPosts_Module_Processor_CustomSectionBlocks::class, PoP_LocationPosts_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLL_LIST],
        );
        foreach ($routemodules_list as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_LIST,
                ],
            ];
            if ($default_format_section == POP_FORMAT_LIST) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_map = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::class, GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_TAGLOCATIONPOSTS_SCROLLMAP],
        );
        foreach ($routemodules_map as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_MAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_MAP) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }
        $routemodules_horizontalmap = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::class, GD_Custom_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_TAGLOCATIONPOSTS_HORIZONTALSCROLLMAP],
        );
        foreach ($routemodules_horizontalmap as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_HORIZONTALMAP,
                ],
            ];
            if ($default_format_section == POP_FORMAT_HORIZONTALMAP) {
                $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
            }
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoP_CommonPages_EM_Module_MainContentRouteModuleProcessor()
	);
}, 200);
