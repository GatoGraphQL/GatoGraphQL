<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


// By not expending from class AAL_Hook_Base, this code is de-attached from AAL
class PoP_SocialNetwork_Notifications_Hook_Users /* extends AAL_Hook_Base*/
{
    public function __construct()
    {

        // Follows/Unfollows user
        HooksAPIFacade::getInstance()->addAction('gd_followuser', array($this, 'followsUser'));
        HooksAPIFacade::getInstance()->addAction('gd_unfollowuser', array($this, 'unfollowsUser'));

        // parent::__construct();
    }

    public function followsUser($followed_user_id)
    {
        $this->followunfollowsUser($followed_user_id, AAL_POP_ACTION_USER_FOLLOWSUSER);
    }

    public function unfollowsUser($unfollowed_user_id)
    {
        $this->followunfollowsUser($unfollowed_user_id, AAL_POP_ACTION_USER_UNFOLLOWSUSER);
    }

    public function followunfollowsUser($user_id, $action)
    {
        $vars = ApplicationState::getVars();
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        PoP_Notifications_Utils::insertLog(
            array(
                'action'      => $action,
                'object_type' => 'User',
                'user_id'     => $vars['global-userstate']['current-user-id'],
                'object_id'   => $user_id,
                'object_name' => $userTypeAPI->getUserDisplayName($user_id),
            )
        );
    }
}
