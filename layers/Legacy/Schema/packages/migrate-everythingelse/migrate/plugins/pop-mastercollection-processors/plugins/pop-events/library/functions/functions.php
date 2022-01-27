<?php
use PoPCMSSchema\Events\Facades\EventTypeAPIFacade;

// \PoP\Root\App::addFilter('gdPostParentpageid', 'gdEmPostParentpageidImpl', 10, 2);
// function gdEmPostParentpageidImpl($pageid, $post_id)
// {
//     $eventTypeAPI = EventTypeAPIFacade::getInstance();
//     if ($eventTypeAPI->isEvent($post_id)) {
//         if ($eventTypeAPI->isFutureEvent($post_id)) {
//             return POP_EVENTS_ROUTE_EVENTS;
//         }
//         return POP_EVENTS_ROUTE_PASTEVENTS;
//     }

//     return $pageid;
// }
