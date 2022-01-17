<?php
use PoPCMSSchema\UserState\CheckpointSets\UserStateCheckpointSets;

trait PoP_LocationPostLinksCreation_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK,
                POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK,
            )
        );
    }

    // function getCheckpointConfiguration() {
    public function getCheckpoints()
    {
        return array(
            POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK => UserStateCheckpointSets::LOGGEDIN_STATIC,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_STATIC),
            POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK => POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT),
        );
    }

    public function needsTargetId()
    {
        return array(
            POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK => true,
        );
    }
}
