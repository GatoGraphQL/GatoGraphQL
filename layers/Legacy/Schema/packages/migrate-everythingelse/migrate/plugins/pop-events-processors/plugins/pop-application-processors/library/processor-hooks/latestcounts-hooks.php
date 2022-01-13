<?php
use PoPSchema\Events\Constants\Scopes;
use PoPSchema\Events\Facades\EventTypeAPIFacade;

class PoP_Events_Events_LatestCounts_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
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
                    $classes[] = $event_post_type . '-' . Scopes::FUTURE;
                    $classes[] = $event_post_type . '-' . Scopes::CURRENT;
                    $classes[] = $event_post_type . '-' . Scopes::PAST;
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
