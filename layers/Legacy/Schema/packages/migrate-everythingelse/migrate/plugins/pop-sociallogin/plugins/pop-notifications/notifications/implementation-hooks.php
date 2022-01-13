<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_SocialLogin_Notifications_ImplementationHooks
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
        new WSL_AAL_PoP_Hook_Users();
    }
}

/**
 * Initialize
 */
new PoP_SocialLogin_Notifications_ImplementationHooks();
