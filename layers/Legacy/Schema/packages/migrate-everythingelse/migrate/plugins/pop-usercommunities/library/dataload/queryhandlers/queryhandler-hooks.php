<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoPSchema\UserRoles\Facades\UserRoleTypeAPIFacade;

define('GD_DATALOAD_USER_ROLES', 'roles');

class PoP_UserCommunities_UserStance_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_UserLogin_DataLoad_QueryInputOutputHandler_Hooks:user-feedback',
            array($this, 'getUserFeedback')
        );
    }

    public function getUserFeedback($user_feedback)
    {
        $user_roles = array();
        if (\PoP\Root\App::getState('is-user-logged-in')) {
            $userID = \PoP\Root\App::getState('current-user-id');

            // array_values so that it discards the indexes: if will transform an array into an object
            $userRoleTypeAPI = UserRoleTypeAPIFacade::getInstance();
            $user_roles = array_values(
                array_values(
                    array_intersect(
                        gdRoles(),
                        $userRoleTypeAPI->getUserRoles($userID)
                    )
                )
            );
        }

        $user_feedback[GD_DATALOAD_USER_ROLES] = $user_roles;
        return $user_feedback;
    }
}

/**
 * Initialization
 */
new PoP_UserCommunities_UserStance_Hooks();
