<?php

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
            POP_USERLOGIN_ROUTE_LOSTPWD => [$this->getDoingPostUserNotLoggedInAggregateCheckpoint()],
            POP_USERLOGIN_ROUTE_LOSTPWDRESET => [$this->getDoingPostUserNotLoggedInAggregateCheckpoint()],
            POP_USERLOGIN_ROUTE_LOGGEDINUSERDATA => [$this->getDoingPostUserLoggedInAggregateCheckpoint()],
        );
    }
}
