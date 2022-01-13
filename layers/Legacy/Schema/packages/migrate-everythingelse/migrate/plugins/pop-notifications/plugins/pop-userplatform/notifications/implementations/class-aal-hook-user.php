<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// By not expending from class AAL_Hook_Base, this code is de-attached from AAL
class PoP_Notifications_UserPlatform_Hook_Users /* extends AAL_Hook_Base*/
{
    public function __construct()
    {

        // User welcome message (function implemented already, but must connect it with the hook)
        \PoP\Root\App::getHookManager()->addAction(
            'gd_createupdate_user:additionalsCreate',
            array(PoP_Notifications_UserPlatform_Utils::class, 'welcomeMessage')
        );

        // Changed password
        \PoP\Root\App::getHookManager()->addAction(
            'gd_changepassword_user',
            array($this, 'changedPassword'),
            10,
            1
        );

        // Updated profile
        \PoP\Root\App::getHookManager()->addAction(
            'gd_createupdate_user:additionalsUpdate',
            array($this, 'updatedProfile'),
            10,
            1
        );

        // parent::__construct();
    }

    public function changedPassword($user_id)
    {
        PoP_Notifications_Utils::logUserAction($user_id, AAL_POP_ACTION_USER_CHANGEDPASSWORD);
    }

    public function updatedProfile($user_id)
    {
        PoP_Notifications_Utils::logUserAction($user_id, AAL_POP_ACTION_USER_UPDATEDPROFILE);
    }
}
