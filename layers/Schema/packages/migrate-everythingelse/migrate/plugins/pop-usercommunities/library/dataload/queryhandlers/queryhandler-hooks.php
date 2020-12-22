<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\UserRoles\Facades\UserRoleTypeDataResolverFacade;
use PoP\ComponentModel\State\ApplicationState;

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
        $vars = ApplicationState::getVars();
        if ($vars['global-userstate']['is-user-logged-in']) {
            $userID = $vars['global-userstate']['current-user-id'];

            // array_values so that it discards the indexes: if will transform an array into an object
            $userRoleTypeDataResolver = UserRoleTypeDataResolverFacade::getInstance();
            $user_roles = array_values(
                array_values(
                    array_intersect(
                        gdRoles(),
                        $userRoleTypeDataResolver->getUserRoles($userID)
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
