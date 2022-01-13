<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * User Communities
 */
// Add the Profile Role when creating a new User Account with WSL
\PoP\Root\App::getHookManager()->addFilter('wsl_hook_process_login_alter_wp_insert_user_data', 'gdWslHookProcessLoginAlterWpInsertUserData');
function gdWslHookProcessLoginAlterWpInsertUserData($userdata)
{
    $userdata['role'] = GD_ROLE_PROFILE;
    return $userdata;
}
