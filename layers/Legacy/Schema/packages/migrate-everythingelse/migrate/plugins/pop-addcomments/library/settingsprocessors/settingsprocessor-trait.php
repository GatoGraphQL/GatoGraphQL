<?php
use PoPCMSSchema\UserState\CheckpointSets\UserStateCheckpointSets;

trait PoP_AddComments_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_ADDCOMMENTS_ROUTE_ADDCOMMENT,
            )
        );
    }

    // function getCheckpointConfiguration() {
    public function getCheckpoints()
    {
        return array(
            POP_ADDCOMMENTS_ROUTE_ADDCOMMENT => UserStateCheckpointSets::LOGGEDIN_STATIC,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_STATIC),
        );
    }
}
