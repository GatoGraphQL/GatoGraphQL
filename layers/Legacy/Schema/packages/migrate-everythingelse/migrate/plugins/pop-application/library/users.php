<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

function userNameUpdated($user_id)
{
    \PoP\Root\App::getHookManager()->doAction('userNameUpdated', $user_id);
}

\PoP\Root\App::getHookManager()->addAction('userNameUpdated', 'saveUserDisplayName', 10, 1);
function saveUserDisplayName($user_id)
{
    $display_name = calculateBestDisplayName($user_id);

    // Update display name. This function must be executed at the end (after updating first and last names)
    $updates = array(
        'ID' => $user_id,
        'display_name' => $display_name,
    );

    wp_update_user($updates);

    // The $display_name also works for nickname: it is needed for searching (can search nickname, but not display_name)
    update_user_meta($user_id, 'nickname', $display_name);
}


/**
 * Returns the best suitable 'Nice name' and 'Display name'
 * Returns: Array[0] = display_name, Array[1] = nice_name
 */
function calculateBestDisplayName($user_id)
{
    $userTypeAPI = UserTypeAPIFacade::getInstance();
    $first_name = $userTypeAPI->getUserFirstname($user_id);
    $last_name = $userTypeAPI->getUserLastname($user_id);
    $user_login = $userTypeAPI->getUserLogin($user_id);
  
    if (!$first_name && !$last_name) {
        $name = $user_login;
    } elseif (!$first_name) {
        $name = $last_name;
    } elseif (!$last_name) {
        $name = $first_name;
    } else {
        $name = sprintf("%s %s", $first_name, $last_name);
    }
    
    return \PoP\Root\App::getHookManager()->applyFilters(
        'calculateBestDisplayName',
        $name,
        $user_id
    );
}
