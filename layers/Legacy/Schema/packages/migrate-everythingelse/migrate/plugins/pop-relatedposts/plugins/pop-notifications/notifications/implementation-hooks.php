<?php

class PoP_RelatedPosts_Notifications_ImplementationHooks
{
    public function __construct()
    {
        \PoP\Root\App::addAction(
            'popcomponent:notifications:init',
            array($this, 'addNotificationHooks')
        );
    }

    public function addNotificationHooks()
    {
        new PoP_RelatedPosts_Notifications_Hook_Posts();
    }
}

/**
 * Initialize
 */
new PoP_RelatedPosts_Notifications_ImplementationHooks();
