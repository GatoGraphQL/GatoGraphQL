<?php

use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Events\Facades\EventTypeAPIFacade;

class PoPTheme_Wassup_Events_CAP_Module_SideInfoContentPageSectionComponentRoutingProcessor extends PoP_Module_SideInfoContentPageSectionComponentRoutingProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        $ret = array();

        $eventTypeAPI = EventTypeAPIFacade::getInstance();

        // Past single event
        $components = array(
            POP_ROUTE_AUTHORS => [PoP_Events_CoAuthors_Module_Processor_SidebarMultiples::class, PoP_Events_CoAuthors_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_SINGLE_PASTEVENT_POSTAUTHORSSIDEBAR],
        );
        foreach ($components as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
                'conditions' => [
                    'routing' => [
                        'queried-object-post-type' => $eventTypeAPI->getEventCustomPostType(),
                        'queried-object-is-past-event' => true,
                    ],
                ],
            ];
        }

        // Future and current single event
        $components = array(
            POP_ROUTE_AUTHORS => [PoP_Events_CoAuthors_Module_Processor_SidebarMultiples::class, PoP_Events_CoAuthors_Module_Processor_SidebarMultiples::COMPONENT_MULTIPLE_SINGLE_EVENT_POSTAUTHORSSIDEBAR],
        );
        foreach ($components as $route => $component) {
            $ret[CustomPostRequestNature::CUSTOMPOST][$route][] = [
                'component' => $component,
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
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new PoPTheme_Wassup_Events_CAP_Module_SideInfoContentPageSectionComponentRoutingProcessor()
	);
}, 200);
