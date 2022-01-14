<?php

use PoPSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPSchema\Events\Facades\EventTypeAPIFacade;

class PoPTheme_Wassup_Events_SocialNetwork_Module_SideInfoContentPageSectionRouteModuleProcessor extends PoP_Module_SideInfoContentPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $eventTypeAPI = EventTypeAPIFacade::getInstance();

        // Past single event
        $modules = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_Events_SocialNetwork_Module_Processor_SidebarMultiples::class, PoP_Events_SocialNetwork_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_PASTEVENT_RECOMMENDEDBYSIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'module' => $module,
                'conditions' => [
                    'routing' => [
                        'queried-object-post-type' => $eventTypeAPI->getEventCustomPostType(),
                        'queried-object-is-past-event' => true,
                    ],
                ],
            ];
        }

        // Future and current single event
        $modules = array(
            POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => [PoP_Events_SocialNetwork_Module_Processor_SidebarMultiples::class, PoP_Events_SocialNetwork_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SINGLE_EVENT_RECOMMENDEDBYSIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'module' => $module,
                'conditions' => [
                    'routing' => [
                        'queried-object-post-type' => $eventTypeAPI->getEventCustomPostType(),
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
		new PoPTheme_Wassup_Events_SocialNetwork_Module_SideInfoContentPageSectionRouteModuleProcessor()
	);
}, 200);
