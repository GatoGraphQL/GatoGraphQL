<?php

class PoP_Domain_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_DOMAIN_ROUTE_LOADERS_INITIALIZEDOMAIN,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_DOMAIN_ROUTE_LOADERS_INITIALIZEDOMAIN => false,
        );
    }
}

/**
 * Initialization
 */
new PoP_Domain_UserState_Module_SettingsProcessor();
