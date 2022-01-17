<?php

class PoP_LocationPostsCreation_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST => PoP_ContentCreation_UserState_Utils::requireUserStateForContentcreationPages(),
        );
    }
}

/**
 * Initialization
 */
new PoP_LocationPostsCreation_UserState_Module_SettingsProcessor();
