<?php
use PoPCMSSchema\UserState\CheckpointSets\UserStateCheckpointSets;

trait AAL_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS,
                POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD,
                POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD,
                POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASUNREAD,
            )
        );
    }

    public function needsTargetId()
    {
        return array(
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD => true,
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASUNREAD => true,
        );
    }

    // function getCheckpointConfiguration() {
    public function getCheckpoints()
    {
        return array(
            // POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS => PoP_UserState_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPUSERSTATE_CHECKPOINTCONFIGURATION_REQUIREUSERSTATE),
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASUNREAD => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
        );
    }
}
