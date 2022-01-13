<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoPSchema\Events\Facades\EventTypeAPIFacade;

HooksAPIFacade::getInstance()->addFilter('get_event_edit_url', 'maybeGetEventLinkEditUrl', 10, 2);
function maybeGetEventLinkEditUrl($url, $post_id)
{
    $eventTypeAPI = EventTypeAPIFacade::getInstance();
    $event = $eventTypeAPI->getEvent($post_id);
    if (eventHasCategory($event, POP_EVENTLINKS_CAT_EVENTLINKS)) {
        return RouteUtils::getRouteURL(POP_EVENTLINKSCREATION_ROUTE_EDITEVENTLINK);
    }

    return $url;
}
