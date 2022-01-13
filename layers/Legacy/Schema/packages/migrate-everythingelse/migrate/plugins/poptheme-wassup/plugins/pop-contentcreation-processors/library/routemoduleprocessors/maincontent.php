<?php

use PoP\Routing\RouteNatures;

class PoPTheme_Wassup_ContentCreation_Module_MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $routemodules = array(
            POP_CONTENTCREATION_ROUTE_FLAG => [PoP_ContentCreation_Module_Processor_Blocks::class, PoP_ContentCreation_Module_Processor_Blocks::MODULE_BLOCK_FLAG],
            POP_CONTENTCREATION_ROUTE_ADDCONTENT => [PoP_Module_Processor_CustomMenuMultiples::class, PoP_Module_Processor_CustomMenuMultiples::MODULE_MULTIPLE_MENU_BODY_ADDCONTENT],
        );
        foreach ($routemodules as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
        }

        $default_format_mycontent = PoP_Application_Utils::getDefaultformatByScreen(POP_SCREEN_MYCONTENT);

        $routemodules_mycontent = array(
            POP_CONTENTCREATION_ROUTE_MYCONTENT => [PoP_ContentCreation_Module_Processor_MySectionBlocks::class, PoP_ContentCreation_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCONTENT_TABLE_EDIT],
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
            POP_CONTENTCREATION_ROUTE_MYCONTENT => [PoP_ContentCreation_Module_Processor_MySectionBlocks::class, PoP_ContentCreation_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCONTENT_SCROLL_SIMPLEVIEWPREVIEW],
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
            POP_CONTENTCREATION_ROUTE_MYCONTENT => [PoP_ContentCreation_Module_Processor_MySectionBlocks::class, PoP_ContentCreation_Module_Processor_MySectionBlocks::MODULE_BLOCK_MYCONTENT_SCROLL_FULLVIEWPREVIEW],
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
		new PoPTheme_Wassup_ContentCreation_Module_MainContentRouteModuleProcessor()
	);
}, 200);
