<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

define('GD_DATALOAD_USER_AVATAR', 'avatar');

class PoP_UserAvatar_UserStance_Hooks
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
        $avatar_user_id = POP_AVATAR_GENERICAVATARUSER;

        $vars = ApplicationState::getVars();
        $user_logged_in = $vars['global-userstate']['is-user-logged-in'];
        if ($user_logged_in) {
            $cmsusersresolver = \PoPSchema\Users\ObjectPropertyResolverFactory::getInstance();
            $avatar_user_id = $vars['global-userstate']['current-user-id'];
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
