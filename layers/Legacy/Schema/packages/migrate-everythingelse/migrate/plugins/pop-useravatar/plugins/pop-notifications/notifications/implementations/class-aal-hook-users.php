<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


// By not expending from class AAL_Hook_Base, this code is de-attached from AAL
class AAL_PoP_UserAvatar_Hook_Users /* extends AAL_Hook_Base*/
{
    public function __construct()
    {

        // Updated photo
        \PoP\Root\App::getHookManager()->addAction('gd_useravatar_update:additionals', array($this, 'updatedPhoto'), 10, 1);

        // parent::__construct();
    }

    public function updatedPhoto($user_id)
    {
        PoP_Notifications_Utils::logUserAction($user_id, AAL_POP_ACTION_USER_UPDATEDPHOTO);
    }
}
