<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Events\Facades\EventTypeAPIFacade;

class PoP_Events_Events_LatestCounts_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'latestcounts:allcontent:classes',
            array($this, 'getAllcontentClasses')
        );
    }

    public function getAllcontentClasses($classes)
    {
        if (defined('POP_TAXONOMIES_INITIALIZED') && PoP_Application_Taxonomy_ConfigurationUtils::hookAllcontentComponents()) {
            if (defined('POP_EVENTS_CAT_ALL') && POP_EVENTS_CAT_ALL) {
                $eventTypeAPI = EventTypeAPIFacade::getInstance();
                $event_post_type = $eventTypeAPI->getEventCustomPostType();
                $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
                if (in_array($event_post_type, $cmsapplicationpostsapi->getAllcontentPostTypes())) {
                    $classes[] = $event_post_type.'-'.POP_EVENTS_SCOPE_FUTURE;
                    $classes[] = $event_post_type.'-'.POP_EVENTS_SCOPE_CURRENT;
                    $classes[] = $event_post_type.'-'.POP_EVENTS_SCOPE_PAST;
                }
            }
        }

        return $classes;
    }
}

/**
 * Initialization
 */
new PoP_Events_Events_LatestCounts_Hooks();
