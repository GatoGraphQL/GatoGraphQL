<?php
use PoPCMSSchema\UserState\CheckpointSets\UserStateCheckpointSets;

class PoP_UserPlatform_SettingsProcessor_CheckpointHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'ModuleProcessor:checkpoints',
            $this->overrideCheckpoints(...)
        );
    }

    public function overrideCheckpoints($checkpoints)
    {

        // Add the checkpoint condition of verifying that the user has Profile role
        if ($checkpoints == UserStateCheckpointSets::LOGGEDIN_STATIC) {
            return POPUSERPLATFORM_CHECKPOINTCONFIGURATION_LOGGEDINPROFILE_STATIC;//PoP_UserPlatform_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPUSERPLATFORM_CHECKPOINTCONFIGURATION_LOGGEDINPROFILE_STATIC);
        } elseif ($checkpoints == UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER) {
            return POPUSERPLATFORM_CHECKPOINTCONFIGURATION_LOGGEDINPROFILE_DATAFROMSERVER;//PoP_UserPlatform_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPUSERPLATFORM_CHECKPOINTCONFIGURATION_LOGGEDINPROFILE_DATAFROMSERVER);
        }

        return $checkpoints;
    }
}

/**
 * Initialization
 */
new PoP_UserPlatform_SettingsProcessor_CheckpointHooks();
