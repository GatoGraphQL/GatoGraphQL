<?php

class PoP_AAL_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS => true,
        );
    }
}

/**
 * Initialization
 */
new PoP_AAL_UserState_Module_SettingsProcessor();
