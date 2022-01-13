<?php

use PoP\Routing\RouteNatures;
use PoP\Routing\Routes as RoutingRoutes;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;

class PoP_Module_SideInfoContentPageSectionRouteModuleProcessor extends PoP_Module_SideInfoContentPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_ROUTE_AUTHORS => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_POST_POSTAUTHORSSIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[CustomPostRouteNatures::CUSTOMPOST][$route][] = ['module' => $module];
        }

        $modules = array(
            RoutingRoutes::$MAIN => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_TAG_MAINCONTENT_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[TagRouteNatures::TAG][$route][] = ['module' => $module];
        }

        $modules = array(
            RoutingRoutes::$MAIN => [PoP_Blog_Module_Processor_SidebarMultiples::class, PoP_Blog_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR],
            POP_ROUTE_DESCRIPTION => [PoP_Blog_Module_Processor_SidebarMultiples::class, PoP_Blog_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHOR_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[UserRouteNatures::USER][$route][] = ['module' => $module];
        }

        return $ret;
    }

    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        $ret = array();

        // Default for Single
        $ret[CustomPostRouteNatures::CUSTOMPOST][] = [
            'module' => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_POST_SIDEBAR]
        ];

        // Default for Author
        $ret[UserRouteNatures::USER][] = [
            'module' => [PoP_Blog_Module_Processor_SidebarMultiples::class, PoP_Blog_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHOR_SIDEBAR]
        ];

        // Default for Tag
        $ret[TagRouteNatures::TAG][] = [
            'module' => [PoP_Module_Processor_CustomSidebarDataloads::class, PoP_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_TAG_SIDEBAR]
        ];

        // Default for Home
        // Allow GetPoP website to change the sidebar, since it is changing the homeroute
        $home_module = \PoP\Root\App::applyFilters(
            'PoPTheme_Wassup_PageSectionSettingsProcessor:sideinfo_home:blockgroup',
            [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_HOMESECTION_CONTENT_SIDEBAR]
        );
        $ret[RouteNatures::HOME][] = [
            'module' => $home_module,
        ];

        return $ret;
    }

    /**
     * @return array<array<string, string[]>>
     */
    public function getModulesVarsProperties(): array
    {
        $ret = array();

        // For the body, instead of adding no SideInfo, add the one that causes the SideInfo to close. This way, if we open a route without Sideinfo (eg: Add Post), from one which does (eg: Posts), then make it close, otherwise the Sideinfo from the 1st route will remain open
        $ret[] = [
            'module' => [PoP_Module_Processor_Codes::class, PoP_Module_Processor_Codes::MODULE_CODE_EMPTYSIDEINFO],
        ];

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoP_Module_SideInfoContentPageSectionRouteModuleProcessor()
	);
}, 200);
