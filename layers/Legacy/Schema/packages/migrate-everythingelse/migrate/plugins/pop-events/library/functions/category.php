<?php
use PoPCMSSchema\Events\Facades\EventTypeAPIFacade;

\PoP\Root\App::addFilter('pop_component:allcontent:tax_query_items', 'popEmAllcontentTaxqueryItems');
function popEmAllcontentTaxqueryItems($tax_query_items)
{
    if (POP_EVENTS_CAT_ALL) {
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        if (in_array($eventTypeAPI->getEventCustomPostType(), $cmsapplicationpostsapi->getAllcontentPostTypes())) {
            $tax_query_items[] = array(
                'taxonomy' => $eventTypeAPI->getEventCategoryTaxonomy(),
                'terms' => array(
                    POP_EVENTS_CAT_ALL,
                ),
            );
        }
    }

    return $tax_query_items;
}
