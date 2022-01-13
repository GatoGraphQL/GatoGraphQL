<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class URE_PoP_Notifications_ImplementationHooks
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
        new URE_AAL_PoP_Hook_Users();
    }
}

/**
 * Initialization
 */
new URE_PoP_Notifications_ImplementationHooks();
