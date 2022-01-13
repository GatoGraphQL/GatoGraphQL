<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_SocialNetwork_Notifications_ImplementationHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addAction(
            'popcomponent:notifications:init',
            array($this, 'addNotificationHooks')
        );
    }

    public function addNotificationHooks()
    {
        new PoP_SocialNetwork_Notifications_Hook_Posts();
        new PoP_SocialNetwork_Notifications_Hook_Tags();
        new PoP_SocialNetwork_Notifications_Hook_Users();
    }
}

/**
 * Initialize
 */
new PoP_SocialNetwork_Notifications_ImplementationHooks();
