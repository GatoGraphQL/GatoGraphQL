<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Notifications_UserAvatar_ImplementationHooks
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
        new AAL_PoP_UserAvatar_Hook_Users();
    }
}

/**
 * Initialize
 */
new PoP_Notifications_UserAvatar_ImplementationHooks();
