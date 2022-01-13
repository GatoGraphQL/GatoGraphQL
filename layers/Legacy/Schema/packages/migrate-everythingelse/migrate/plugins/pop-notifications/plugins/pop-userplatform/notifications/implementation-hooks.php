<?php

class PoP_Notifications_UserPlatform_ImplementationHooks
{
    public function __construct()
    {

        // Add this library's hooks for AAL
        \PoP\Root\App::getHookManager()->addAction(
            'popcomponent:notifications:init',
            array($this, 'addNotificationHooks')
        );
    }

    public function addNotificationHooks()
    {
        new PoP_Notifications_UserPlatform_Hook_Users();
    }
}

/**
 * Initialize
 */
new PoP_Notifications_UserPlatform_ImplementationHooks();
