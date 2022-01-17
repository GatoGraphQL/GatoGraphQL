<?php

class PoP_System_Theme_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_SYSTEM_ROUTE_SYSTEM_GENERATETHEME,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_SYSTEM_ROUTE_SYSTEM_GENERATETHEME => false,
        );
    }
}

/**
 * Initialization
 */
new PoP_System_Theme_UserState_Module_SettingsProcessor();
