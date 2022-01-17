<?php
use PoPCMSSchema\UserState\CheckpointSets\UserStateCheckpointSets;

trait PoP_LocationPostsCreation_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS,
                POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST,
                POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST,
            )
        );
    }

    // function getCheckpointConfiguration() {
    public function getCheckpoints()
    {
        return array(
            POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST => UserStateCheckpointSets::LOGGEDIN_STATIC,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_STATIC),
            POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST => POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT),
        );
    }

    public function needsTargetId()
    {
        return array(
            POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST => true,
        );
    }
}
