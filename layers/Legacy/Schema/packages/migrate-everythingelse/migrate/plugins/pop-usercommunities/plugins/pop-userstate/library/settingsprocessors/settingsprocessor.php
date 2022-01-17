<?php

class PoP_UserCommunities_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS => false,
        );
    }
}

/**
 * Initialization
 */
new PoP_UserCommunities_UserState_Module_SettingsProcessor();
