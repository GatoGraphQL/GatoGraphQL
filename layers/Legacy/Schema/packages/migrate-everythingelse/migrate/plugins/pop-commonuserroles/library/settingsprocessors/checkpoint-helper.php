<?php
use PoPCMSSchema\UserState\Checkpoints\UserStateCheckpoint;

const POPCOMMONUSERROLES_CHECKPOINTCONFIGURATION_PROFILEORGANIZATION_DATAFROMSERVER = array(
    [UserStateCheckpoint::class, UserStateCheckpoint::USERLOGGEDIN],
    [PoPCore_Dataload_Checkpoint::class, PoPCore_Dataload_Checkpoint::CHECKPOINT_PROFILEACCESS],
    [GD_URE_Dataload_UserCheckpoint::class, GD_URE_Dataload_UserCheckpoint::CHECKPOINT_LOGGEDINUSER_ISPROFILEORGANIZATION],
);
const POPCOMMONUSERROLES_CHECKPOINTCONFIGURATION_PROFILEINDIVIDUAL_DATAFROMSERVER = array(
    [UserStateCheckpoint::class, UserStateCheckpoint::USERLOGGEDIN],
    [PoPCore_Dataload_Checkpoint::class, PoPCore_Dataload_Checkpoint::CHECKPOINT_PROFILEACCESS],
    [GD_URE_Dataload_UserCheckpoint::class, GD_URE_Dataload_UserCheckpoint::CHECKPOINT_LOGGEDINUSER_ISPROFILEINDIVIDUAL],
);
