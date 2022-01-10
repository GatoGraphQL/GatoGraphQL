<?php

use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;

class PoPTheme_Wassup_SocialNetwork_SocialNetwork_Module_SideInfoContentPageSectionRouteModuleProcessor extends PoP_Module_SideInfoContentPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_LocationPosts_SocialNetwork_Module_Processor_SidebarMultiples::class, PoP_LocationPosts_SocialNetwork_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_LOCATIONPOST_RECOMMENDEDBYSIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[CustomPostRouteNatures::CUSTOMPOST][$route][] = [
                'module' => $module,
                'conditions' => [
                    'routing' => [
                        'queried-object-post-type' => POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST,
                    ],
                ],
            ];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoPTheme_Wassup_SocialNetwork_SocialNetwork_Module_SideInfoContentPageSectionRouteModuleProcessor()
	);
}, 200);
