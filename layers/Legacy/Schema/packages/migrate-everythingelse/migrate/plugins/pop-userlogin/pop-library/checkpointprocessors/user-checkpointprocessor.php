<?php
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;
use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\UserRoles\Facades\UserRoleTypeAPIFacade;

class GD_UserLogin_Dataload_UserCheckpointProcessor extends AbstractCheckpointProcessor
{
    public const CHECKPOINT_LOGGEDINUSER_ISADMINISTRATOR = 'checkpoint-loggedinuser-isadministrator';

    public function getCheckpointsToProcess(): array
    {
        return array(
            [self::class, self::CHECKPOINT_LOGGEDINUSER_ISADMINISTRATOR],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?\PoP\ComponentModel\Checkpoint\CheckpointError
    {
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_LOGGEDINUSER_ISADMINISTRATOR:
                $user_id = \PoP\Root\App::getState('current-user-id');
                $userRoleTypeAPI = UserRoleTypeAPIFacade::getInstance();
                if (!$userRoleTypeAPI->hasRole($user_id, 'administrator')) {
                    return new Error('userisnotadmin');
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}

