<?php

class PoP_PersistentDefinitionsSystem_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_SYSTEM_ROUTE_SYSTEM_SAVEDEFINITIONFILE,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_SYSTEM_ROUTE_SYSTEM_SAVEDEFINITIONFILE => false,
        );
    }
}

/**
 * Initialization
 */
new PoP_PersistentDefinitionsSystem_UserState_Module_SettingsProcessor();
