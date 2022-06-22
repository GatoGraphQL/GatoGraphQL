<?php
use PoP\Engine\Checkpoints\RequestCheckpoint;
use PoPCMSSchema\UserState\Checkpoints\UserStateCheckpoint;

const POPUSERPLATFORM_CHECKPOINTCONFIGURATION_LOGGEDINPROFILE_STATIC = array(
	[RequestCheckpoint::class, RequestCheckpoint::DOING_POST],
    [UserStateCheckpoint::class, UserStateCheckpoint::USERLOGGEDIN],
    [PoPCore_Dataload_Checkpoint::class, PoPCore_Dataload_Checkpoint::CHECKPOINT_PROFILEACCESS_SUBMIT],
);
const POPUSERPLATFORM_CHECKPOINTCONFIGURATION_LOGGEDINPROFILE_DATAFROMSERVER = array(
    [UserStateCheckpoint::class, UserStateCheckpoint::USERLOGGEDIN],
    [PoPCore_Dataload_Checkpoint::class, PoPCore_Dataload_Checkpoint::CHECKPOINT_PROFILEACCESS],
);
