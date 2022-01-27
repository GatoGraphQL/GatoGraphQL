<?php

use PoPCMSSchema\UserState\CheckpointSets\UserStateCheckpointSets;

trait PoP_UserLogin_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_USERLOGIN_ROUTE_LOGGEDINUSERDATA,
                POP_USERLOGIN_ROUTE_LOGIN,
                POP_USERLOGIN_ROUTE_LOSTPWD,
                POP_USERLOGIN_ROUTE_LOSTPWDRESET,
                POP_USERLOGIN_ROUTE_LOGOUT,
            )
        );
    }

    // function getCheckpointConfiguration() {
    public function getCheckpoints()
    {
        return array(
            // POP_USERLOGIN_ROUTE_LOGIN => PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPUSERLOGIN_CHECKPOINTCONFIGURATION_REQUIREUSERSTATEONDOINGPOST),
            // POP_USERLOGIN_ROUTE_LOGOUT => PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPUSERLOGIN_CHECKPOINTCONFIGURATION_REQUIREUSERSTATEONDOINGPOST),
            POP_USERLOGIN_ROUTE_LOSTPWD => UserStateCheckpointSets::NOTLOGGEDIN,
            POP_USERLOGIN_ROUTE_LOSTPWDRESET => UserStateCheckpointSets::NOTLOGGEDIN,
            POP_USERLOGIN_ROUTE_LOGGEDINUSERDATA => UserStateCheckpointSets::LOGGEDIN_STATIC,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_STATIC/*UserStateCheckpointSets::LOGGEDIN_STATIC_REQUIRESUSERSTATE*/),
        );
    }
}
