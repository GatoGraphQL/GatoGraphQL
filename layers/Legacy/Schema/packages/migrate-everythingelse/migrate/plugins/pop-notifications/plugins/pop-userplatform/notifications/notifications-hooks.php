<?php

class PoP_Notifications_UserPlatform_NotificationHooks
{
    public function __construct()
    {

        // Hook into the API: Notification Actions
        \PoP\Root\App::addFilter(
            'AAL_PoP_API:notifications:userspecific:actions',
            array($this, 'getUserspecificActions')
        );
    }

    public function getUserspecificActions($actions)
    {

        // User-specific Notifications:
        return array_merge(
            $actions,
            array(
                // Notify the user when:
                // "Welcome User" message
                AAL_POP_ACTION_USER_WELCOMENEWUSER,
            )
        );
    }
}

/**
 * Initialize
 */
new PoP_Notifications_UserPlatform_NotificationHooks();
