<?php
use PoP\Engine\Checkpoints\RequestCheckpoint;
use PoPCMSSchema\UserState\Checkpoints\UserStateCheckpoint;

const POPUSERCOMMUNITIES_CHECKPOINTCONFIGURATION_PROFILECOMMUNITY_STATIC = array(
    [RequestCheckpoint::class, RequestCheckpoint::DOING_POST],
    [UserStateCheckpoint::class, UserStateCheckpoint::USERLOGGEDIN],
    [PoPCore_Dataload_Checkpoint::class, PoPCore_Dataload_Checkpoint::CHECKPOINT_PROFILEACCESS],
    [PoP_UserCommunities_Dataload_UserCheckpoint::class, PoP_UserCommunities_Dataload_UserCheckpoint::CHECKPOINT_LOGGEDINUSER_ISCOMMUNITY],
);
const POPUSERCOMMUNITIES_CHECKPOINTCONFIGURATION_PROFILECOMMUNITY_DATAFROMSERVER = array(
    [UserStateCheckpoint::class, UserStateCheckpoint::USERLOGGEDIN],
    [PoPCore_Dataload_Checkpoint::class, PoPCore_Dataload_Checkpoint::CHECKPOINT_PROFILEACCESS],
    [PoP_UserCommunities_Dataload_UserCheckpoint::class, PoP_UserCommunities_Dataload_UserCheckpoint::CHECKPOINT_LOGGEDINUSER_ISCOMMUNITY],
);
const POPUSERCOMMUNITIES_CHECKPOINTCONFIGURATION_EDITMEMBERSHIP_DATAFROMSERVER = array(
    [UserStateCheckpoint::class, UserStateCheckpoint::USERLOGGEDIN],
    [PoPCore_Dataload_Checkpoint::class, PoPCore_Dataload_Checkpoint::CHECKPOINT_PROFILEACCESS],
    [PoP_UserCommunities_Dataload_UserCheckpoint::class, PoP_UserCommunities_Dataload_UserCheckpoint::CHECKPOINT_LOGGEDINUSER_ISCOMMUNITY],
    [PoP_UserCommunities_Dataload_UserCheckpoint::class, PoP_UserCommunities_Dataload_UserCheckpoint::CHECKPOINT_EDITMEMBERSHIPNONCE],
    [PoP_UserCommunities_Dataload_UserCheckpoint::class, PoP_UserCommunities_Dataload_UserCheckpoint::CHECKPOINT_EDITINGCOMMUNITYMEMBER],
);
