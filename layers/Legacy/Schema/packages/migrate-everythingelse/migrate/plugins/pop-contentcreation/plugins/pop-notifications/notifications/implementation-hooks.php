<?php

class PoP_ContentCreation_Notifications_ImplementationHooks
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
        new PoP_ContentCreation_Notifications_Hook_Posts();
    }
}

/**
 * Initialize
 */
new PoP_ContentCreation_Notifications_ImplementationHooks();
