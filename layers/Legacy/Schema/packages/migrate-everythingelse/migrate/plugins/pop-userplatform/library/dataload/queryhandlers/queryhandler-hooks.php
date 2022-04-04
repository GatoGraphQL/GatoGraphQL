<?php
use PoP\ComponentModel\State\ApplicationState;

define('GD_DATALOAD_USER_ATTRIBUTES', 'userattributes');

class PoP_UserPlatform_UserStance_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_UserLogin_DataLoad_QueryInputOutputHandler_Hooks:user-feedback',
            $this->getUserFeedback(...)
        );
    }

    public function getUserFeedback($user_feedback)
    {
        $user_attributes = array();
        $user_logged_in = \PoP\Root\App::getState('is-user-logged-in');
        if ($user_logged_in) {
            $user_id = \PoP\Root\App::getState('current-user-id');
            
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
