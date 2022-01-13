<?php

use PoP\Routing\RouteNatures;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;

class PoPTheme_Wassup_AddHighlights_Module_SideInfoContentPageSectionRouteModuleProcessor extends PoP_Module_SideInfoContentPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_SidebarMultiples::class, PoP_AddHighlights_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_POST_HIGHLIGHTSSIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[CustomPostRouteNatures::CUSTOMPOST][$route][] = ['module' => $module];
        }

        $modules = array(
            POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => [PoP_AddHighlights_Module_Processor_SidebarMultiples::class, PoP_AddHighlights_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_HIGHLIGHTS_SIDEBAR],
            POP_ADDHIGHLIGHTS_ROUTE_MYHIGHLIGHTS => [PoP_AddHighlights_Module_Processor_SidebarMultiples::class, PoP_AddHighlights_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_MYHIGHLIGHTS_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[RouteNatures::GENERIC][$route][] = ['module' => $module];
        }

        return $ret;
    }

    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        $ret[CustomPostRouteNatures::CUSTOMPOST][] = [
            'module' => [PoP_AddHighlights_Module_Processor_SidebarMultiples::class, PoP_AddHighlights_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_HIGHLIGHT_SIDEBAR],
            'conditions' => [
                'routing' => [
                    'queried-object-post-type' => POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT,
                ],
            ],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoPTheme_Wassup_AddHighlights_Module_SideInfoContentPageSectionRouteModuleProcessor()
	);
}, 200);
