<?php

class PoP_CommonAutomatedEmails_EM_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY => false,
        );
    }
}

/**
 * Initialization
 */
new PoP_CommonAutomatedEmails_EM_UserState_Module_SettingsProcessor();
