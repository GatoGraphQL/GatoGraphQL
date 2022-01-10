<?php
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;
use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\UserRoles\Facades\UserRoleTypeAPIFacade;

class GD_UserLogin_Dataload_UserCheckpointProcessor extends AbstractCheckpointProcessor
{
    public const CHECKPOINT_LOGGEDINUSER_ISADMINISTRATOR = 'checkpoint-loggedinuser-isadministrator';

    public function getCheckpointsToProcess(): array
    {
        return array(
            [self::class, self::CHECKPOINT_LOGGEDINUSER_ISADMINISTRATOR],
        );
    }

    public function validateCheckpoint(array $checkpoint): ?Error
    {
        $vars = ApplicationState::getVars();
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_LOGGEDINUSER_ISADMINISTRATOR:
                $user_id = $vars['current-user-id'];
                $userRoleTypeAPI = UserRoleTypeAPIFacade::getInstance();
                if (!$userRoleTypeAPI->hasRole($user_id, 'administrator')) {
                    return new Error('userisnotadmin');
                }
                break;
        }

        return parent::validateCheckpoint($checkpoint);
    }
}

