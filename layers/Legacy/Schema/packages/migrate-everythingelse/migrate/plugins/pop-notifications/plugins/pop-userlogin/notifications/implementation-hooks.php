<?php

class PoP_Notifications_UserLogin_ImplementationHooks
{
    public function __construct()
    {
        \PoP\Root\App::addAction(
            'popcomponent:notifications:init',
            $this->addNotificationHooks(...)
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
