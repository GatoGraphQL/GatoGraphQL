<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

define('GD_DATALOAD_USER_AVATAR', 'avatar');

class PoP_UserAvatar_UserStance_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_UserLogin_DataLoad_QueryInputOutputHandler_Hooks:user-feedback',
            array($this, 'getUserFeedback')
        );
    }

    public function getUserFeedback($user_feedback)
    {
        $avatar_user_id = POP_AVATAR_GENERICAVATARUSER;

        $user_logged_in = \PoP\Root\App::getState('is-user-logged-in');
        if ($user_logged_in) {
            $avatar_user_id = \PoP\Root\App::getState('current-user-id');
        }

        $avatar = gdGetAvatar($avatar_user_id, GD_AVATAR_SIZE_100);
        $user_feedback[GD_DATALOAD_USER_AVATAR] = $avatar['src'];
        
        return $user_feedback;
    }
}

/**
 * Initialization
 */
new PoP_UserAvatar_UserStance_Hooks();
