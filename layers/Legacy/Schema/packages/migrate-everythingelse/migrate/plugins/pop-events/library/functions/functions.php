<?php
use PoP\ComponentModel\ModuleProcessors\Constants;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\Events\Facades\EventTypeAPIFacade;

// HooksAPIFacade::getInstance()->addFilter('gd_dataload:post_types', 'gdEmAddEventPosttype');
function gdEmAddEventPosttype($post_types)
{
    $eventTypeAPI = EventTypeAPIFacade::getInstance();
    $post_types[] = $eventTypeAPI->getEventCustomPostType();
    return $post_types;
}

function eventHasCategory($event, $cat)
{
    $eventTypeAPI = EventTypeAPIFacade::getInstance();
    $categories = $eventTypeAPI->getCategories($event);
    return isset($categories[$cat]);
}

// HooksAPIFacade::getInstance()->addFilter('gdGetCategories', 'gdEmGetCategories', 10, 2);
// function gdEmGetCategories($categories, $post_id)
// {
//     $eventTypeAPI = EventTypeAPIFacade::getInstance();
//     if ($eventTypeAPI->isEvent($post_id)) {
//         $event = $eventTypeAPI->getEvent($post_id);
//         return array_keys($eventTypeAPI->getCategories($event));
//     }

//     return $categories;
// }

HooksAPIFacade::getInstance()->addFilter('gd_postname', 'gdEmPostnameImpl', 10, 2);
function gdEmPostnameImpl($name, $post_id)
{
    $eventTypeAPI = EventTypeAPIFacade::getInstance();
    if ($eventTypeAPI->isEvent($post_id)) {
        return TranslationAPIFacade::getInstance()->__('Event', 'poptheme-wassup');
    }

    return $name;
}
HooksAPIFacade::getInstance()->addFilter('gd_posticon', 'gdEmPosticonImpl', 10, 2);
function gdEmPosticonImpl($icon, $post_id)
{
    $eventTypeAPI = EventTypeAPIFacade::getInstance();
    if ($eventTypeAPI->isEvent($post_id)) {
        return getRouteIcon(POP_EVENTS_ROUTE_EVENTS, false);
    }

    return $icon;
}

HooksAPIFacade::getInstance()->addFilter(
    Constants::HOOK_QUERYDATA_WHITELISTEDPARAMS,
    function($params) {
        $params[] = GD_URLPARAM_YEAR;
        $params[] = GD_URLPARAM_MONTH;
        return $params;
    }
);
