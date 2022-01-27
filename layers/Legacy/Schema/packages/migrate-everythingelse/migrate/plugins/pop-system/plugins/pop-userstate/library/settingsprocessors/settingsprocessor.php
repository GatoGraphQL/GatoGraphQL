<?php

class PoP_System_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_SYSTEM_ROUTE_SYSTEM_BUILD,
                POP_SYSTEM_ROUTE_SYSTEM_GENERATE,
                POP_SYSTEM_ROUTE_SYSTEM_INSTALL,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_SYSTEM_ROUTE_SYSTEM_BUILD => false,
            POP_SYSTEM_ROUTE_SYSTEM_GENERATE => false,
            POP_SYSTEM_ROUTE_SYSTEM_INSTALL => false,
        );
    }
}

/**
 * Initialization
 */
new PoP_System_UserState_Module_SettingsProcessor();
