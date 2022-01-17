<?php
use PoPCMSSchema\UserState\CheckpointSets\UserStateCheckpointSets;

trait PoP_PostsCreation_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_POSTSCREATION_ROUTE_ADDPOST,
                POP_POSTSCREATION_ROUTE_EDITPOST,
                POP_POSTSCREATION_ROUTE_MYPOSTS,
            )
        );
    }

    public function needsTargetId()
    {
        return array(
            POP_POSTSCREATION_ROUTE_EDITPOST => true,
        );
    }

    // function getCheckpointConfiguration() {
    public function getCheckpoints()
    {
        return array(
            POP_POSTSCREATION_ROUTE_ADDPOST => UserStateCheckpointSets::LOGGEDIN_STATIC,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_STATIC),
            POP_POSTSCREATION_ROUTE_EDITPOST => POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT),
            POP_POSTSCREATION_ROUTE_MYPOSTS => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
        );
    }
}
