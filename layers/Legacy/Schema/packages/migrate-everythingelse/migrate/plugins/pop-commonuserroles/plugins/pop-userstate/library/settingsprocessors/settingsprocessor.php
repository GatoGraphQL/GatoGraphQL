<?php

class PoP_CommonUserRoles_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_COMMONUSERROLES_ROUTE_ADDPROFILEORGANIZATION,
                POP_COMMONUSERROLES_ROUTE_ADDPROFILEINDIVIDUAL,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_COMMONUSERROLES_ROUTE_ADDPROFILEORGANIZATION => false,
            POP_COMMONUSERROLES_ROUTE_ADDPROFILEINDIVIDUAL => false,
        );
    }
}

/**
 * Initialization
 */
new PoP_CommonUserRoles_UserState_Module_SettingsProcessor();
