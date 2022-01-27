<?php
use PoPCMSSchema\UserState\CheckpointSets\UserStateCheckpointSets;

trait PoP_UserStance_Module_SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_USERSTANCE_ROUTE_MYSTANCES,
                POP_USERSTANCE_ROUTE_ADDSTANCE,
                POP_USERSTANCE_ROUTE_EDITSTANCE,
                POP_USERSTANCE_ROUTE_ADDOREDITSTANCE,
                POP_USERSTANCE_ROUTE_STANCES,
                POP_USERSTANCE_ROUTE_STANCES_PRO,
                POP_USERSTANCE_ROUTE_STANCES_AGAINST,
                POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
                POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL,
                POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL,
                POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL,
                POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE,
                POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE,
                POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE,
                POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
                POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
            )
        );
    }

    // function getCheckpointConfiguration() {
    public function getCheckpoints()
    {
        return array(
            POP_USERSTANCE_ROUTE_ADDSTANCE => UserStateCheckpointSets::LOGGEDIN_STATIC,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_STATIC),
            POP_USERSTANCE_ROUTE_MYSTANCES => UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER),
            POP_USERSTANCE_ROUTE_EDITSTANCE => POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT),
            // If first loading the page: do not let it fail checkpoint validation, hence LOGGEDIN_STATIC. However, it must always get the data from the server, hence REQUIREUSERSTATE
            // When doing a submit, handle it as the usual LOGGEDIN_DATAFROMSERVER
            POP_USERSTANCE_ROUTE_ADDOREDITSTANCE => doingPost() ?
            UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER : //PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER) :
            UserStateCheckpointSets::LOGGEDIN_STATIC,//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_STATIC/*UserStateCheckpointSets::LOGGEDIN_STATIC_REQUIRESUSERSTATE*/),
        );
    }

    public function needsTargetId()
    {
        return array(
            POP_USERSTANCE_ROUTE_EDITSTANCE => true,
        );
    }
}
