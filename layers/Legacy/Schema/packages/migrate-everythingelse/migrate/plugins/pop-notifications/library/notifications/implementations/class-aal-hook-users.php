<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


// By not expending from class AAL_Hook_Base, this code is de-attached from AAL
class PoP_Notifications_Hook_Users /* extends AAL_Hook_Base*/
{
    public function __construct()
    {

        // When a user is deleted from the system, delete all notifications from/for the user
        HooksAPIFacade::getInstance()->addAction(
            'popcms:deleteUser', 
            array($this, 'deleteUser'), 
            10, 
            1
        );

        // parent::__construct();
    }

    public function deleteUser($user_id)
    {

        // AAL_Main::instance()->api->clearUser($user_id);
        PoP_Notifications_API::clearUser($user_id);
    }
}
