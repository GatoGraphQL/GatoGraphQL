<?php
use PoP\Engine\CheckpointProcessors\RequestCheckpointProcessor;
use PoPCMSSchema\UserState\CheckpointProcessors\UserStateCheckpointProcessor;

const POP_SOCIALLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_STATIC = array(
	[RequestCheckpointProcessor::class, RequestCheckpointProcessor::DOING_POST],
    [UserStateCheckpointProcessor::class, UserStateCheckpointProcessor::USERLOGGEDIN],
    [GD_WSL_Dataload_UserCheckpointProcessor::class, GD_WSL_Dataload_UserCheckpointProcessor::CHECKPOINT_NONSOCIALLOGINUSER],
);
