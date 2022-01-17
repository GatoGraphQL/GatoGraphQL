<?php

class PoP_LocationPostLinksCreation_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK => PoP_ContentCreation_UserState_Utils::requireUserStateForContentcreationPages(),
        );
    }
}

/**
 * Initialization
 */
new PoP_LocationPostLinksCreation_UserState_Module_SettingsProcessor();
