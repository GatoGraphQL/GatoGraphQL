<?php
use PoPCMSSchema\UserState\CheckpointProcessors\UserStateCheckpointProcessor;

const POPCOMMONUSERROLES_CHECKPOINTCONFIGURATION_PROFILEORGANIZATION_DATAFROMSERVER = array(
    [UserStateCheckpointProcessor::class, UserStateCheckpointProcessor::USERLOGGEDIN],
    [PoPCore_Dataload_CheckpointProcessor::class, PoPCore_Dataload_CheckpointProcessor::CHECKPOINT_PROFILEACCESS],
    [GD_URE_Dataload_UserCheckpointProcessor::class, GD_URE_Dataload_UserCheckpointProcessor::CHECKPOINT_LOGGEDINUSER_ISPROFILEORGANIZATION],
);
const POPCOMMONUSERROLES_CHECKPOINTCONFIGURATION_PROFILEINDIVIDUAL_DATAFROMSERVER = array(
    [UserStateCheckpointProcessor::class, UserStateCheckpointProcessor::USERLOGGEDIN],
    [PoPCore_Dataload_CheckpointProcessor::class, PoPCore_Dataload_CheckpointProcessor::CHECKPOINT_PROFILEACCESS],
    [GD_URE_Dataload_UserCheckpointProcessor::class, GD_URE_Dataload_UserCheckpointProcessor::CHECKPOINT_LOGGEDINUSER_ISPROFILEINDIVIDUAL],
);
