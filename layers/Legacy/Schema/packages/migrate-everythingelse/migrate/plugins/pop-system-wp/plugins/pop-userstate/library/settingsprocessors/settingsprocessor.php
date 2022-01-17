<?php

class PoP_SystemWP_CMSWP_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_SYSTEMWP_ROUTE_SYSTEM_ACTIVATEPLUGINS,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_SYSTEMWP_ROUTE_SYSTEM_ACTIVATEPLUGINS => false,
        );
    }
}

/**
 * Initialization
 */
new PoP_SystemWP_CMSWP_UserState_Module_SettingsProcessor();
