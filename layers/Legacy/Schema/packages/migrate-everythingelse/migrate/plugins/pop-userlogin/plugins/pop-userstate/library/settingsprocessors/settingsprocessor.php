<?php

class PoP_UserLogin_UserState_Module_SettingsProcessor extends \PoPCMSSchema\UserState\Settings\SettingsProcessorBase
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_USERLOGIN_ROUTE_LOGIN,
                POP_USERLOGIN_ROUTE_LOSTPWD,
                POP_USERLOGIN_ROUTE_LOSTPWDRESET,
                POP_USERLOGIN_ROUTE_LOGOUT,
                POP_USERLOGIN_ROUTE_LOGGEDINUSERDATA,
            )
        );
    }

    public function requiresUserState()
    {
        return array(
            POP_USERLOGIN_ROUTE_LOGIN => doingPost(),
            POP_USERLOGIN_ROUTE_LOSTPWD => false,
            POP_USERLOGIN_ROUTE_LOSTPWDRESET => false,
            POP_USERLOGIN_ROUTE_LOGOUT => doingPost(),
            POP_USERLOGIN_ROUTE_LOGGEDINUSERDATA => true,
        );
    }
}

/**
 * Initialization
 */
new PoP_UserLogin_UserState_Module_SettingsProcessor();
