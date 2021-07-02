<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

define('GD_DATALOAD_USER_ATTRIBUTES', 'userattributes');

class PoP_UserPlatform_UserStance_Hooks
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
        $vars = ApplicationState::getVars();
        $user_attributes = array();
        $user_logged_in = $vars['global-userstate']['is-user-logged-in'];
        if ($user_logged_in) {
            $user_id = $vars['global-userstate']['current-user-id'];
            
            // User attributes: eg: is WSL user? Needed to hide "Change Password" link for these users
            $user_attributes = array_values(
                array_values(
                    array_intersect(
                        gdUserAttributes(),
                        gdGetUserattributes($user_id)
                    )
                )
            );
        }
        
        $user_feedback[GD_DATALOAD_USER_ATTRIBUTES] = $user_attributes;
        return $user_feedback;
    }
}

/**
 * Initialization
 */
new PoP_UserPlatform_UserStance_Hooks();
