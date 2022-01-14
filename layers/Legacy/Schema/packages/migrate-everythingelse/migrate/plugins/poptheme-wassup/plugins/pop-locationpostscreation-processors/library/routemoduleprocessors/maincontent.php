<?php

use PoP\Root\Routing\RouteNatures;

class PoP_LocationPostsCreation_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST => [GD_Custom_EM_Module_Processor_CreateUpdatePostBlocks::class, GD_Custom_EM_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_LOCATIONPOST_CREATE],
            POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST => [GD_Custom_EM_Module_Processor_CreateUpdatePostBlocks::class, GD_Custom_EM_Module_Processor_CreateUpdatePostBlocks::MODULE_BLOCK_LOCATIONPOST_UPDATE],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
        }

        $default_format_mycontent = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);

        $routemodules_mycontent = array(
            POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS => [GD_Custom_EM_Module_Processor_MySectionBlocks::class, GD_Custom_EM_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYLOCATIONPOSTS_TABLE_EDIT],
        );
        foreach ($routemodules_mycontent as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_TABLE,
                ],
            ];
            if ($default_format_mycontent == POP_FORMAT_TABLE) {
                $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_mycontent_simpleviewpreviews = array(
            POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS => [GD_Custom_EM_Module_Processor_MySectionBlocks::class, GD_Custom_EM_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYLOCATIONPOSTS_SCROLL_SIMPLEVIEWPREVIEW],
        );
        foreach ($routemodules_mycontent_simpleviewpreviews as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_SIMPLEVIEW,
                ],
            ];
            if ($default_format_mycontent == POP_FORMAT_SIMPLEVIEW) {
                $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
            }
        }
        $routemodules_mycontent_fullviewpreviews = array(
            POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS => [GD_Custom_EM_Module_Processor_MySectionBlocks::class, GD_Custom_EM_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYLOCATIONPOSTS_SCROLL_FULLVIEWPREVIEW],
        );
        foreach ($routemodules_mycontent_fullviewpreviews as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = [
                'module' => $module,
                'conditions' => [
                    'format' => POP_FORMAT_FULLVIEW,
                ],
            ];
            if ($default_format_mycontent == POP_FORMAT_FULLVIEW) {
                $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
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
		new PoP_LocationPostsCreation_Module_MainContentRouteModuleProcessor()
	);
}, 200);
