<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_ReferencedPosts_Notifications_NotificationHooks
{
    public function __construct()
    {

        // Hook into the API: Notification Actions
        HooksAPIFacade::getInstance()->addFilter(
            'AAL_PoP_API:notifications:useractivityposts:actions',
            array($this, 'getUseractivitypostsActions')
        );

        HooksAPIFacade::getInstance()->addFilter(
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
                // A thought on TPP is created from a post by the user
                AAL_POP_ACTION_POST_CREATEDSTANCE,
            )
        );
    }

    public function addActions($actions)
    {
        $actions[] = AAL_POP_ACTION_POST_CREATEDSTANCE;
        return $actions;
    }
}

/**
 * Initialize
 */
new PoP_ReferencedPosts_Notifications_NotificationHooks();
