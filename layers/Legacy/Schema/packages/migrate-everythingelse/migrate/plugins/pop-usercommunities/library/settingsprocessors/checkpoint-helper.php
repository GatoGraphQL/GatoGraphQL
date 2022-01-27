<?php
use PoP\Engine\CheckpointProcessors\RequestCheckpointProcessor;
use PoPCMSSchema\UserState\CheckpointProcessors\UserStateCheckpointProcessor;

const POPUSERCOMMUNITIES_CHECKPOINTCONFIGURATION_PROFILECOMMUNITY_STATIC = array(
    [RequestCheckpointProcessor::class, RequestCheckpointProcessor::DOING_POST],
    [UserStateCheckpointProcessor::class, UserStateCheckpointProcessor::USERLOGGEDIN],
    [PoPCore_Dataload_CheckpointProcessor::class, PoPCore_Dataload_CheckpointProcessor::CHECKPOINT_PROFILEACCESS],
    [PoP_UserCommunities_Dataload_UserCheckpointProcessor::class, PoP_UserCommunities_Dataload_UserCheckpointProcessor::CHECKPOINT_LOGGEDINUSER_ISCOMMUNITY],
);
const POPUSERCOMMUNITIES_CHECKPOINTCONFIGURATION_PROFILECOMMUNITY_DATAFROMSERVER = array(
    [UserStateCheckpointProcessor::class, UserStateCheckpointProcessor::USERLOGGEDIN],
    [PoPCore_Dataload_CheckpointProcessor::class, PoPCore_Dataload_CheckpointProcessor::CHECKPOINT_PROFILEACCESS],
    [PoP_UserCommunities_Dataload_UserCheckpointProcessor::class, PoP_UserCommunities_Dataload_UserCheckpointProcessor::CHECKPOINT_LOGGEDINUSER_ISCOMMUNITY],
);
const POPUSERCOMMUNITIES_CHECKPOINTCONFIGURATION_EDITMEMBERSHIP_DATAFROMSERVER = array(
    [UserStateCheckpointProcessor::class, UserStateCheckpointProcessor::USERLOGGEDIN],
    [PoPCore_Dataload_CheckpointProcessor::class, PoPCore_Dataload_CheckpointProcessor::CHECKPOINT_PROFILEACCESS],
    [PoP_UserCommunities_Dataload_UserCheckpointProcessor::class, PoP_UserCommunities_Dataload_UserCheckpointProcessor::CHECKPOINT_LOGGEDINUSER_ISCOMMUNITY],
    [PoP_UserCommunities_Dataload_UserCheckpointProcessor::class, PoP_UserCommunities_Dataload_UserCheckpointProcessor::CHECKPOINT_EDITMEMBERSHIPNONCE],
    [PoP_UserCommunities_Dataload_UserCheckpointProcessor::class, PoP_UserCommunities_Dataload_UserCheckpointProcessor::CHECKPOINT_EDITINGCOMMUNITYMEMBER],
);
