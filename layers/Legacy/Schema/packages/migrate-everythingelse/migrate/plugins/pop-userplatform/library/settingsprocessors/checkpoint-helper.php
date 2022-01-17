<?php
use PoP\Engine\CheckpointProcessors\RequestCheckpointProcessor;
use PoPCMSSchema\UserState\CheckpointProcessors\UserStateCheckpointProcessor;

const POPUSERPLATFORM_CHECKPOINTCONFIGURATION_LOGGEDINPROFILE_STATIC = array(
	[RequestCheckpointProcessor::class, RequestCheckpointProcessor::DOING_POST],
    [UserStateCheckpointProcessor::class, UserStateCheckpointProcessor::USERLOGGEDIN],
    [PoPCore_Dataload_CheckpointProcessor::class, PoPCore_Dataload_CheckpointProcessor::CHECKPOINT_PROFILEACCESS_SUBMIT],
);
const POPUSERPLATFORM_CHECKPOINTCONFIGURATION_LOGGEDINPROFILE_DATAFROMSERVER = array(
    [UserStateCheckpointProcessor::class, UserStateCheckpointProcessor::USERLOGGEDIN],
    [PoPCore_Dataload_CheckpointProcessor::class, PoPCore_Dataload_CheckpointProcessor::CHECKPOINT_PROFILEACCESS],
);
