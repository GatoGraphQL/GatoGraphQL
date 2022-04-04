<?php

class PoP_AddComments_Notifications_ImplementationHooks
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
        new PoP_AddComments_Notifications_Hook_Comments();
    }
}

/**
 * Initialize
 */
new PoP_AddComments_Notifications_ImplementationHooks();
