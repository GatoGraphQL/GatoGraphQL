<?php

use PoP\Root\Routing\RequestNature;
use PoP\Root\Routing\Routes as RoutingRoutes;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

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
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = ['module' => $module];
        }

        $modules = array(
            RoutingRoutes::$MAIN => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_TAG_MAINCONTENT_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[TagRequestNature::TAG][$route][] = ['module' => $module];
        }

        $modules = array(
            RoutingRoutes::$MAIN => [PoP_Blog_Module_Processor_SidebarMultiples::class, PoP_Blog_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHORMAINCONTENT_SIDEBAR],
            POP_ROUTE_DESCRIPTION => [PoP_Blog_Module_Processor_SidebarMultiples::class, PoP_Blog_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHOR_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[UserRequestNature::USER][$route][] = ['module' => $module];
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
        $ret[CustomPostRequestNature::CUSTOMPOST][] = [
            'module' => [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_POST_SIDEBAR]
        ];

        // Default for Author
        $ret[UserRequestNature::USER][] = [
            'module' => [PoP_Blog_Module_Processor_SidebarMultiples::class, PoP_Blog_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_AUTHOR_SIDEBAR]
        ];

        // Default for Tag
        $ret[TagRequestNature::TAG][] = [
            'module' => [PoP_Module_Processor_CustomSidebarDataloads::class, PoP_Module_Processor_CustomSidebarDataloads::MODULE_DATALOAD_TAG_SIDEBAR]
        ];

        // Default for Home
        // Allow GetPoP website to change the sidebar, since it is changing the homeroute
        $home_module = \PoP\Root\App::applyFilters(
            'PoPTheme_Wassup_PageSectionSettingsProcessor:sideinfo_home:blockgroup',
            [PoP_Module_Processor_SidebarMultiples::class, PoP_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_HOMESECTION_CONTENT_SIDEBAR]
        );
        $ret[RequestNature::HOME][] = [
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
