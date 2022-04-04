<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// By not expending from class AAL_Hook_Base, this code is de-attached from AAL
class PoP_Notifications_UserLogin_Hook_Users /* extends AAL_Hook_Base*/
{
    public function __construct()
    {

        // Logged in/out
        \PoP\Root\App::addAction(
            'gd:user:loggedin',
            $this->loggedIn(...),
            10,
            1
        );
        \PoP\Root\App::addAction(
            'gd:user:loggedout',
            $this->loggedOut(...),
            10,
            1
        );

        // parent::__construct();
    }

    public function loggedIn($user_id)
    {
        PoP_Notifications_Utils::logUserAction($user_id, AAL_POP_ACTION_USER_LOGGEDIN);
    }

    public function loggedOut($user_id)
    {
        PoP_Notifications_Utils::logUserAction($user_id, AAL_POP_ACTION_USER_LOGGEDOUT);
    }
}
