<?php
use PoPCMSSchema\UserState\CheckpointProcessors\UserStateCheckpointProcessor;

const POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT = array(
    [UserStateCheckpointProcessor::class, UserStateCheckpointProcessor::USERLOGGEDIN],
    [GD_ContentCreation_Dataload_UserCheckpointProcessor::class, GD_ContentCreation_Dataload_UserCheckpointProcessor::CHECKPOINT_USERCANEDIT],
    [GD_ContentCreation_Dataload_UserCheckpointProcessor::class, GD_ContentCreation_Dataload_UserCheckpointProcessor::CHECKPOINT_EDITPOSTNONCE],
);

const POPUSERPLATFORM_CHECKPOINTCONFIGURATION_LOGGEDINPROFILE_CANEDIT = array(
    [UserStateCheckpointProcessor::class, UserStateCheckpointProcessor::USERLOGGEDIN],
    [PoPCore_Dataload_CheckpointProcessor::class, PoPCore_Dataload_CheckpointProcessor::CHECKPOINT_PROFILEACCESS],
    [GD_ContentCreation_Dataload_UserCheckpointProcessor::class, GD_ContentCreation_Dataload_UserCheckpointProcessor::CHECKPOINT_USERCANEDIT],
    [GD_ContentCreation_Dataload_UserCheckpointProcessor::class, GD_ContentCreation_Dataload_UserCheckpointProcessor::CHECKPOINT_EDITPOSTNONCE],
);
