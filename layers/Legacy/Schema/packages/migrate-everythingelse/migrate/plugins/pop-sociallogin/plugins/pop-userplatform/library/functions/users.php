<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

define('POP_SOCIALLOGIN_USERATTRIBUTE_SOCIALLOGIN', 'sociallogin-user');

/**
 * User Attributes
 */
// Add a class to the body to identify the user as WSL, to hide the "Change Password" link
HooksAPIFacade::getInstance()->addFilter('gdUserAttributes', 'gdWslUserAttributes');
function gdWslUserAttributes($userattributes)
{
    $userattributes[] = POP_SOCIALLOGIN_USERATTRIBUTE_SOCIALLOGIN;
    return $userattributes;
}

HooksAPIFacade::getInstance()->addFilter('gdGetUserattributes', 'gdWslGetUserattributes', 10, 2);
function gdWslGetUserattributes($userattributes, $user_id)
{
    if (isSocialloginUser($user_id)) {
        $userattributes[] = POP_SOCIALLOGIN_USERATTRIBUTE_SOCIALLOGIN;
    }
    return $userattributes;
}
