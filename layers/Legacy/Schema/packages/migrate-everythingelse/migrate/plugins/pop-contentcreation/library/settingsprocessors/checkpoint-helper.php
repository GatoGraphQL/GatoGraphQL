<?php
use PoPCMSSchema\UserState\Checkpoints\UserStateCheckpoint;

const POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_CANEDIT = array(
    [UserStateCheckpoint::class, UserStateCheckpoint::USERLOGGEDIN],
    [GD_ContentCreation_Dataload_UserCheckpoint::class, GD_ContentCreation_Dataload_UserCheckpoint::CHECKPOINT_USERCANEDIT],
    [GD_ContentCreation_Dataload_UserCheckpoint::class, GD_ContentCreation_Dataload_UserCheckpoint::CHECKPOINT_EDITPOSTNONCE],
);

const POPUSERPLATFORM_CHECKPOINTCONFIGURATION_LOGGEDINPROFILE_CANEDIT = array(
    [UserStateCheckpoint::class, UserStateCheckpoint::USERLOGGEDIN],
    [PoPCore_Dataload_Checkpoint::class, PoPCore_Dataload_Checkpoint::CHECKPOINT_PROFILEACCESS],
    [GD_ContentCreation_Dataload_UserCheckpoint::class, GD_ContentCreation_Dataload_UserCheckpoint::CHECKPOINT_USERCANEDIT],
    [GD_ContentCreation_Dataload_UserCheckpoint::class, GD_ContentCreation_Dataload_UserCheckpoint::CHECKPOINT_EDITPOSTNONCE],
);
