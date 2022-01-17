<?php

class PoP_AddComments_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_ADDCOMMENTS_ROUTE_ADDCOMMENT,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_ADDCOMMENTS_ROUTE_ADDCOMMENT => false,
        );
    }
}

/**
 * Initialization
 */
new PoP_AddComments_UserState_Module_SettingsProcessor();
