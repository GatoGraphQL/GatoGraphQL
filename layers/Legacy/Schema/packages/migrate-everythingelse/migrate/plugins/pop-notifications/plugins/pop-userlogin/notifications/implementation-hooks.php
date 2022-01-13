<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Notifications_UserLogin_ImplementationHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction(
            'popcomponent:notifications:init',
            array($this, 'addNotificationHooks')
        );
    }

    public function addNotificationHooks()
    {
        new PoP_Notifications_UserLogin_Hook_Users();
    }
}

/**
 * Initialize
 */
new PoP_Notifications_UserLogin_ImplementationHooks();
