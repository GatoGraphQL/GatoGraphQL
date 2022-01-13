<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\Events\Constants\Scopes;
use PoPSchema\Events\Facades\EventTypeAPIFacade;

HooksAPIFacade::getInstance()->addFilter('pop_modulemanager:multilayout_labels', 'gdEmCustomMultilayoutLabels');
function gdEmCustomMultilayoutLabels($labels)
{
    $eventTypeAPI = EventTypeAPIFacade::getInstance();
    $event_post_type = $eventTypeAPI->getEventCustomPostType();
    $label = '<span class="label label-%s">%s</span>';
    return array_merge(
        array(
            $event_post_type . '-' . Scopes::FUTURE => sprintf(
                $label,
                'future-events',
                getRouteIcon(POP_EVENTS_ROUTE_EVENTS, true) . TranslationAPIFacade::getInstance()->__('Upcoming Event', 'poptheme-wassup')
            ),
            $event_post_type . '-' . Scopes::CURRENT => sprintf(
                $label,
                'current-events',
                getRouteIcon(POP_EVENTS_ROUTE_EVENTS, true) . TranslationAPIFacade::getInstance()->__('Current Event', 'poptheme-wassup')
            ),
            $event_post_type . '-' . Scopes::PAST => sprintf(
                $label,
                'past-events',
                getRouteIcon(POP_EVENTS_ROUTE_PASTEVENTS, true) . TranslationAPIFacade::getInstance()->__('Past Event', 'poptheme-wassup')
            )
        ),
        $labels
    );
}
