<?php
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\UserRoles\Facades\UserRoleTypeDataResolverFacade;
use PoP\ComponentModel\CheckpointProcessors\AbstractCheckpointProcessor;

class GD_UserLogin_Dataload_UserCheckpointProcessor extends AbstractCheckpointProcessor
{
    public const CHECKPOINT_LOGGEDINUSER_ISADMINISTRATOR = 'checkpoint-loggedinuser-isadministrator';

    public function getCheckpointsToProcess()
    {
        return array(
            [self::class, self::CHECKPOINT_LOGGEDINUSER_ISADMINISTRATOR],
        );
    }

    public function process(array $checkpoint)
    {
        $vars = ApplicationState::getVars();
        switch ($checkpoint[1]) {
            case self::CHECKPOINT_LOGGEDINUSER_ISADMINISTRATOR:
                $user_id = $vars['global-userstate']['current-user-id'];
                $userRoleTypeDataResolver = UserRoleTypeDataResolverFacade::getInstance();
                if (!$userRoleTypeDataResolver->hasRole($user_id, 'administrator')) {
                    return new \PoP\ComponentModel\ErrorHandling\Error('userisnotadmin');
                }
                break;
        }

        return parent::process($checkpoint);
    }
}

