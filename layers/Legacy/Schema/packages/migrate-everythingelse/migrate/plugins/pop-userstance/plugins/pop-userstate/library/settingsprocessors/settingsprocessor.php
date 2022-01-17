<?php

class PoP_UserStance_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_USERSTANCE_ROUTE_ADDSTANCE,
                POP_USERSTANCE_ROUTE_ADDOREDITSTANCE,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_USERSTANCE_ROUTE_ADDSTANCE => false,
            POP_USERSTANCE_ROUTE_ADDOREDITSTANCE => true,/*doingPost(),*/
        );
    }
}

/**
 * Initialization
 */
new PoP_UserStance_UserState_Module_SettingsProcessor();
