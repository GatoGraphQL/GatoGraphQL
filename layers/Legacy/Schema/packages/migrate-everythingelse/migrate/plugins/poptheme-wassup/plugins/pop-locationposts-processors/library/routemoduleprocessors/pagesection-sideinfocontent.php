<?php

use PoP\Routing\RouteNatures;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;

class PoPTheme_Wassup_LocationPosts_Module_SideInfoContentPageSectionRouteModuleProcessor extends PoP_Module_SideInfoContentPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoPSP_URE_EM_Module_Processor_SidebarMultiples::class, PoPSP_URE_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHORLOCATIONPOSTS_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
        }

        $modules = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoPSP_URE_EM_Module_Processor_SidebarMultiples::class, PoPSP_URE_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_TAG_LOCATIONPOSTS_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
        }

        $modules = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoPSP_URE_EM_Module_Processor_SidebarMultiples::class, PoPSP_URE_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_TAG_LOCATIONPOSTS_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
        }

        $modules = array(
            POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => [PoPSP_URE_EM_Module_Processor_SidebarMultiples::class, PoPSP_URE_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_LOCATIONPOSTS_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
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
            'module' => [PoPSP_URE_EM_Module_Processor_SidebarMultiples::class, PoPSP_URE_EM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_SIDEBAR],
            'conditions' => [
                'routing' => [
                    'queried-object-post-type' => POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST,
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
		new PoPTheme_Wassup_LocationPosts_Module_SideInfoContentPageSectionRouteModuleProcessor()
	);
}, 200);
