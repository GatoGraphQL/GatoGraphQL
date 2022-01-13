<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

// Do not use the original WSL getAvatar function
HooksAPIFacade::getInstance()->removeFilter('get_avatar', 'wsl_get_wp_user_custom_avatar', 10, 5);

// Instead, check if PoP_UserAvatar is displaying the default avatar, only then use the WSL avatar
// (TemplatManager user-set avatar has priority)
HooksAPIFacade::getInstance()->addFilter('gd_avatar_default', 'gdWslAvatar', 100, 5);
function gdWslAvatar($html, $user, $size, $default, $alt)
{
    $userTypeAPI = UserTypeAPIFacade::getInstance();

    // If passed an object, assume $user->ID
    if (is_object($user)) {
        $user_id = $userTypeAPI->getUserId($user);
    }

    // If passed a number, assume it was a $user_id
    elseif (is_numeric($user)) {
        $user_id = $user;
    }

    // If passed a string and that string returns a user, get the $id
    elseif (is_string($user) && ($user_by_email = $userTypeAPI->getUserByEmail($user))) {
        $user_id = $userTypeAPI->getUserId($user_by_email);
    }

    // User found?
    if ($user_id) {
        if ($wsl_avatar = wsl_get_user_custom_avatar($user_id)) {
            $wsl_html = '<img alt="'. $alt .'" src="' . $wsl_avatar . '" class="avatar avatar-wordpress-social-login avatar-' . $size . ' photo" height="' . $size . '" width="' . $size . '" />';

            // HOOKABLE:
            return HooksAPIFacade::getInstance()->applyFilters('pop_wsl_hook_alter_wp_user_custom_avatar', $wsl_html, $user_id, $wsl_avatar, $html, $user, $size, $default, $alt);
        }
    }

    return $html;
}
