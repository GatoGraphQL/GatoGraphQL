<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_RelatedPosts_Notifications_NotificationHooks
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
            array($this, 'getMarkasreadPostActions')
        );
    }

    public function getUseractivitypostsActions($actions)
    {
        return array_merge(
            $actions,
            array(
                // Notify the user when:
                // - Anyone created a post related to the user's post
                AAL_POP_ACTION_POST_REFERENCEDPOST
            )
        );
    }

    public function getMarkasreadPostActions($actions)
    {
        return array_merge(
            $actions,
            array(
                AAL_POP_ACTION_POST_REFERENCEDPOST,
            )
        );
    }
}

/**
 * Initialize
 */
new PoP_RelatedPosts_Notifications_NotificationHooks();
