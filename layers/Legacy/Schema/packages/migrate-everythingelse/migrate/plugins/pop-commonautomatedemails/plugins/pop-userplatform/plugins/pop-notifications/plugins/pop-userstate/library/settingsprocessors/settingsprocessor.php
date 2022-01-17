<?php

class PoP_CommonAutomatedEmails_AAL_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTNOTIFICATIONS_DAILY,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTNOTIFICATIONS_DAILY => false,
        );
    }
}

/**
 * Initialization
 */
new PoP_CommonAutomatedEmails_AAL_UserState_Module_SettingsProcessor();
