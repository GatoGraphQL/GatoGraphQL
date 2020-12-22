<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class URE_PoP_Notifications_ImplementationHooks
{
    public function __construct()
    {

        // Add this library's hooks for AAL
        HooksAPIFacade::getInstance()->addAction(
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
