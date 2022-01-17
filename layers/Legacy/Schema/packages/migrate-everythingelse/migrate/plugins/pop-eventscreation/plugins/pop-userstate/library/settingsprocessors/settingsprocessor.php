<?php

class PoP_EventsCreation_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_EVENTSCREATION_ROUTE_ADDEVENT,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_EVENTSCREATION_ROUTE_ADDEVENT => PoP_ContentCreation_UserState_Utils::requireUserStateForContentcreationPages(),
        );
    }
}

/**
 * Initialization
 */
new PoP_EventsCreation_UserState_Module_SettingsProcessor();
