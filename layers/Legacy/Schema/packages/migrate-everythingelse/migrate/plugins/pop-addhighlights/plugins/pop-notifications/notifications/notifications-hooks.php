<?php

class PoP_AddHighlights_Notifications_NotificationHooks
{
    public function __construct()
    {

        // Hook into the API: Notification Actions
        \PoP\Root\App::addFilter(
            'AAL_PoP_API:notifications:useractivityposts:actions',
            array($this, 'getUseractivitypostsActions')
        );

        \PoP\Root\App::addFilter(
            'AAL_PoP_API:additional_notifications:markasread:posts:actions',
            array($this, 'addActions')
        );
    }

    public function getUseractivitypostsActions($actions)
    {

        // User-activity Notifications:
        return array_merge(
            $actions,
            array(
                // Notify the user when:
                // An extract is created from a post by the user
                AAL_POP_ACTION_POST_HIGHLIGHTEDFROMPOST,
            )
        );
    }

    public function addActions($actions)
    {
        $actions[] = AAL_POP_ACTION_POST_HIGHLIGHTEDFROMPOST;
        return $actions;
    }
}

/**
 * Initialize
 */
new PoP_AddHighlights_Notifications_NotificationHooks();
