<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_UserStance_Notifications_ImplementationHooks
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
        new PoP_UserStance_Notifications_Hook_Posts();
    }
}

/**
 * Initialize
 */
new PoP_UserStance_Notifications_ImplementationHooks();
