<?php
use PoP\Engine\Route\RouteUtils;
use PoPSchema\Events\Facades\EventTypeAPIFacade;

\PoP\Root\App::addFilter('gd-createupdateutils:edit-url', 'maybeGetEventEditUrl', 10, 2);
function maybeGetEventEditUrl($url, $post_id)
{
    $eventTypeAPI = EventTypeAPIFacade::getInstance();
    if ($eventTypeAPI->isEvent($post_id)) {
        // Allow PoP Event Links Creation to hook in its value
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        return \PoP\Root\App::applyFilters(
            'get_event_edit_url',
            RouteUtils::getRouteURL(POP_EVENTSCREATION_ROUTE_EDITEVENT),
            $post_id
        );
    }

    return $url;
}
