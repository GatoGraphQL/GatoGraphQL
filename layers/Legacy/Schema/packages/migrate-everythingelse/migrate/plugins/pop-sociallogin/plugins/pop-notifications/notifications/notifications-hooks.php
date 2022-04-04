<?php

class PoP_SocialLogin_Notifications_NotificationHooks
{
    public function __construct()
    {

        // Hook into the API: Notification Actions
        \PoP\Root\App::addFilter(
            'AAL_PoP_API:notifications:userspecific:actions',
            $this->getUserspecificActions(...)
        );
    }

    public function getUserspecificActions($actions)
    {

        // User-specific Notifications:
        return array_merge(
            $actions,
            array(
                // Notify the user when:
                // Twitter log-in: request user to update the (fake) email
                WSL_AAL_POP_ACTION_USER_REQUESTCHANGEEMAIL,
            )
        );
    }
}

/**
 * Initialize
 */
new PoP_SocialLogin_Notifications_NotificationHooks();
