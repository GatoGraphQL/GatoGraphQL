<?php

class WSL_SettingsProcessor_CheckpointHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'Wassup_Module_SettingsProcessor:changepwdprofile:checkpoints',
            $this->getChangepwdCheckpoints(...)
        );
    }

    public function getChangepwdCheckpoints($checkpoints)
    {

        // Change the checkpoint: non-WSL users cannot edit their pwd
        return POP_SOCIALLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_STATIC;//PoP_SocialLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POP_SOCIALLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_STATIC);
    }
}

/**
 * Initialization
 */
new WSL_SettingsProcessor_CheckpointHooks();
