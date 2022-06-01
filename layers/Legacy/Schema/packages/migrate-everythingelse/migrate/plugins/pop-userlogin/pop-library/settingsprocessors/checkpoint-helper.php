<?php
use PoPCMSSchema\UserState\Checkpoints\UserStateCheckpoint;

const POPUSERLOGIN_CHECKPOINTCONFIGURATION_LOGGEDIN_ISADMINISTRATOR = array(
    [UserStateCheckpoint::class, UserStateCheckpoint::USERLOGGEDIN],
    [GD_UserLogin_Dataload_UserCheckpoint::class, GD_UserLogin_Dataload_UserCheckpoint::CHECKPOINT_LOGGEDINUSER_ISADMINISTRATOR],
);
