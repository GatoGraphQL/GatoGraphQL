<?php
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoPCMSSchema\UserState\Checkpoints\DoingPostUserLoggedInAggregateCheckpoint;

class PoP_UserPlatform_SettingsProcessor_CheckpointHooks
{
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;
    private ?DoingPostUserLoggedInAggregateCheckpoint $doingPostUserLoggedInAggregateCheckpoint = null;

    final public function setUserLoggedInCheckpoint(UserLoggedInCheckpoint $userLoggedInCheckpoint): void
    {
        $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
    }
    final protected function getUserLoggedInCheckpoint(): UserLoggedInCheckpoint
    {
        /** @var UserLoggedInCheckpoint */
        return $this->userLoggedInCheckpoint ??= $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
    }
    final public function setDoingPostUserLoggedInAggregateCheckpoint(DoingPostUserLoggedInAggregateCheckpoint $doingPostUserLoggedInAggregateCheckpoint): void
    {
        $this->doingPostUserLoggedInAggregateCheckpoint = $doingPostUserLoggedInAggregateCheckpoint;
    }
    final protected function getDoingPostUserLoggedInAggregateCheckpoint(): DoingPostUserLoggedInAggregateCheckpoint
    {
        /** @var DoingPostUserLoggedInAggregateCheckpoint */
        return $this->doingPostUserLoggedInAggregateCheckpoint ??= $this->instanceManager->getInstance(DoingPostUserLoggedInAggregateCheckpoint::class);
    }
    
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'ComponentProcessor:checkpoints',
            $this->overrideCheckpoints(...)
        );
    }

    public function overrideCheckpoints($checkpoints)
    {
        // Add the checkpoint condition of verifying that the user has Profile role
        if ($checkpoints === [$this->getDoingPostUserLoggedInAggregateCheckpoint()]) {
            return POPUSERPLATFORM_CHECKPOINTCONFIGURATION_LOGGEDINPROFILE_STATIC;//PoP_UserPlatform_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPUSERPLATFORM_CHECKPOINTCONFIGURATION_LOGGEDINPROFILE_STATIC);
        }
        if ($checkpoints === [$this->getUserLoggedInCheckpoint()]) {
            return POPUSERPLATFORM_CHECKPOINTCONFIGURATION_LOGGEDINPROFILE_DATAFROMSERVER;//PoP_UserPlatform_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(POPUSERPLATFORM_CHECKPOINTCONFIGURATION_LOGGEDINPROFILE_DATAFROMSERVER);
        }

        return $checkpoints;
    }
}

/**
 * Initialization
 */
new PoP_UserPlatform_SettingsProcessor_CheckpointHooks();
