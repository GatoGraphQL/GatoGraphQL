<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_SocialLogin_Notifications_ImplementationHooks
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
        new WSL_AAL_PoP_Hook_Users();
    }
}

/**
 * Initialize
 */
new PoP_SocialLogin_Notifications_ImplementationHooks();
