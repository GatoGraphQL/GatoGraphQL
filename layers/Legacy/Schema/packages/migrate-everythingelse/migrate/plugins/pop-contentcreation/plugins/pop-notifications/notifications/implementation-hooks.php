<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_ContentCreation_Notifications_ImplementationHooks
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
        new PoP_ContentCreation_Notifications_Hook_Posts();
    }
}

/**
 * Initialize
 */
new PoP_ContentCreation_Notifications_ImplementationHooks();
