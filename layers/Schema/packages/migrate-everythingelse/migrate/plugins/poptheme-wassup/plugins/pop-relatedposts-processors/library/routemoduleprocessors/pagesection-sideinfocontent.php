<?php

use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;

class PoPTheme_Wassup_RelatedPosts_Module_SideInfoContentPageSectionRouteModuleProcessor extends PoP_Module_SideInfoContentPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_POST_RELATEDCONTENTSIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[CustomPostRouteNatures::CUSTOMPOST][$route][] = ['module' => $module];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->add(
		new PoPTheme_Wassup_RelatedPosts_Module_SideInfoContentPageSectionRouteModuleProcessor()
	);
}, 200);
