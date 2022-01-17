<?php

class PoP_EventLinksCreation_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK => PoP_ContentCreation_UserState_Utils::requireUserStateForContentcreationPages(),
        );
    }
}

/**
 * Initialization
 */
new PoP_EventLinksCreation_UserState_Module_SettingsProcessor();
