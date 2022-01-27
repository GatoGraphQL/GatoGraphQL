<?php

class PoP_UserPlatform_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE => false,
        );
    }
}

/**
 * Initialization
 */
new PoP_UserPlatform_UserState_Module_SettingsProcessor();
