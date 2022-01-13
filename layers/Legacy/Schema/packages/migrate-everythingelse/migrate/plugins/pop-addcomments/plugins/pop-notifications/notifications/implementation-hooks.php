<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_AddComments_Notifications_ImplementationHooks
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
        new PoP_AddComments_Notifications_Hook_Comments();
    }
}

/**
 * Initialize
 */
new PoP_AddComments_Notifications_ImplementationHooks();
