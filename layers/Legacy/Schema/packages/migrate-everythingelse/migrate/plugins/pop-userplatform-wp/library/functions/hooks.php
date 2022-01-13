<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_UserPlatformWP_WP_Hooks
{
    public function __construct()
    {

        \PoP\Root\App::getHookManager()->addAction(
            'gd_createupdate_user:additionalsUpdate',
            array($this, 'resetCurrentUser'),
            10,
            1
        );
    }

    public function resetCurrentUser($user_id)
    {
        
        // Force WP to refetch the data for the logged in user
        // Eg: when changing "Do you accept members?" for an Organization, it will add/remove the role COMMUNITY, and if not flushed, the old values will persist
        global $current_user;
        $current_user = null;
    }
}

/**
 * Initialize
 */
new PoP_UserPlatformWP_WP_Hooks();
