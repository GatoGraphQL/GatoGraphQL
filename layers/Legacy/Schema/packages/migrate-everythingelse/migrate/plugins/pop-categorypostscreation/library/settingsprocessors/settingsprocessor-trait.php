<?php
use PoPCMSSchema\UserState\CheckpointSets\UserStateCheckpointSets;

trait PoP_CategoryPostsCreation_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS00,
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS01,
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS02,
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS03,
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS04,
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS05,
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS06,
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS07,
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS08,
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS09,
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS10,
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS11,
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS12,
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS13,
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS14,
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS15,
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS16,
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS17,
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS18,
                POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS19,
            )
        );
    }

    // function getCheckpointConfiguration() {
    public function getCheckpoints()
    {
        return array(
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS00 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS01 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS02 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS03 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS04 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS05 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS06 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS07 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS08 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS09 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS10 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS11 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS12 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS13 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS14 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS15 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS16 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS17 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS18 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_CATEGORYPOSTSCREATION_ROUTE_MYCATEGORYPOSTS19 => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
        );
    }
}
