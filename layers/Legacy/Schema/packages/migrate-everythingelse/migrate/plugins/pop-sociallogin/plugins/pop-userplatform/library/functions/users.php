<?php

define('POP_SOCIALLOGIN_USERATTRIBUTE_SOCIALLOGIN', 'sociallogin-user');

/**
 * User Attributes
 */
// Add a class to the body to identify the user as WSL, to hide the "Change Password" link
\PoP\Root\App::getHookManager()->addFilter('gdUserAttributes', 'gdWslUserAttributes');
function gdWslUserAttributes($userattributes)
{
    $userattributes[] = POP_SOCIALLOGIN_USERATTRIBUTE_SOCIALLOGIN;
    return $userattributes;
}

\PoP\Root\App::getHookManager()->addFilter('gdGetUserattributes', 'gdWslGetUserattributes', 10, 2);
function gdWslGetUserattributes($userattributes, $user_id)
{
    if (isSocialloginUser($user_id)) {
        $userattributes[] = POP_SOCIALLOGIN_USERATTRIBUTE_SOCIALLOGIN;
    }
    return $userattributes;
}
