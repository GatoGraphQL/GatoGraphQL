<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_SocialNetworkProcessors_Hooks_MyPreferences
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_Module_Processor_UserMultipleComponents:emailnotifications:modules',
            array($this, 'getEmailnotificationsForminputgroups')
        );
    }

    public function getEmailnotificationsForminputgroups($modules)
    {
        return array_merge(
            $modules,
            array(
                [PoP_SocialNetwork_Module_Processor_UserMultipleComponents::class, PoP_SocialNetwork_Module_Processor_UserMultipleComponents::MODULE_MULTICOMPONENT_EMAILNOTIFICATIONS_NETWORK],
                [PoP_SocialNetwork_Module_Processor_UserMultipleComponents::class, PoP_SocialNetwork_Module_Processor_UserMultipleComponents::MODULE_MULTICOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC],
            )
        );
    }
}


/**
 * Initialization
 */
new PoP_SocialNetworkProcessors_Hooks_MyPreferences();
