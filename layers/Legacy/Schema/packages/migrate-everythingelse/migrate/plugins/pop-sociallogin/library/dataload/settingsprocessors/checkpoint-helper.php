<?php
use PoP\Engine\Checkpoints\RequestCheckpoint;
use PoPCMSSchema\UserState\Checkpoints\UserStateCheckpoint;

const POP_SOCIALLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_STATIC = array(
	[RequestCheckpoint::class, RequestCheckpoint::DOING_POST],
    [UserStateCheckpoint::class, UserStateCheckpoint::USERLOGGEDIN],
    [GD_WSL_Dataload_UserCheckpoint::class, GD_WSL_Dataload_UserCheckpoint::CHECKPOINT_NONSOCIALLOGINUSER],
);
