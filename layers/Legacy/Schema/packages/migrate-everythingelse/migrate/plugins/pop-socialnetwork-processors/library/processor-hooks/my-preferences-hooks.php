<?php

class PoP_SocialNetworkProcessors_Hooks_MyPreferences
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_UserMultipleComponents:emailnotifications:modules',
            $this->getEmailnotificationsForminputgroups(...)
        );
    }

    public function getEmailnotificationsForminputgroups($components)
    {
        return array_merge(
            $components,
            array(
                [PoP_SocialNetwork_Module_Processor_UserMultipleComponents::class, PoP_SocialNetwork_Module_Processor_UserMultipleComponents::COMPONENT_MULTICOMPONENT_EMAILNOTIFICATIONS_NETWORK],
                [PoP_SocialNetwork_Module_Processor_UserMultipleComponents::class, PoP_SocialNetwork_Module_Processor_UserMultipleComponents::COMPONENT_MULTICOMPONENT_EMAILNOTIFICATIONS_SUBSCRIBEDTOPIC],
            )
        );
    }
}


/**
 * Initialization
 */
new PoP_SocialNetworkProcessors_Hooks_MyPreferences();
