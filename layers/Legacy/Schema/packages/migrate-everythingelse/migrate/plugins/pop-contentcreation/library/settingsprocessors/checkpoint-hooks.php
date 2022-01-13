<?php

class PoP_ContentCreation_SettingsProcessor_CheckpointHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'ModuleProcessor:checkpoints',
            array($this, 'overrideCheckpoints')
        );
    }

    public function overrideCheckpoints($checkpoints)
    {

        // Add the checkpoint condition of verifying that the user has Profile role
        if ($checkpoints == POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT) {
            return POPUSERPLATFORM_CHECKPOINTCONFIGURATION_LOGGEDINPROFILE_CANEDIT;//PoP_UserPlatform_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPUSERPLATFORM_CHECKPOINTCONFIGURATION_LOGGEDINPROFILE_CANEDIT);
        }

        return $checkpoints;
    }
}

/**
 * Initialization
 */
new PoP_ContentCreation_SettingsProcessor_CheckpointHooks();
