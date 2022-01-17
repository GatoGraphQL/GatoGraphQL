<?php
use PoPCMSSchema\UserState\CheckpointProcessors\UserStateCheckpointProcessor;

const POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_ISADMINISTRATOR = array(
    [UserStateCheckpointProcessor::class, UserStateCheckpointProcessor::USERLOGGEDIN],
    [GD_UserLogin_Dataload_UserCheckpointProcessor::class, GD_UserLogin_Dataload_UserCheckpointProcessor::CHECKPOINT_LOGGEDINUSER_ISADMINISTRATOR],
);
