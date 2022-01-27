<?php

class PoP_PostsCreation_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_POSTSCREATION_ROUTE_ADDPOST,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_POSTSCREATION_ROUTE_ADDPOST => PoP_ContentCreation_UserState_Utils::requireUserStateForContentcreationPages(),
        );
    }
}

/**
 * Initialization
 */
new PoP_PostsCreation_UserState_Module_SettingsProcessor();
