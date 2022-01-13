<?php
use PoP\ComponentModel\State\ApplicationState;

function getSocialloginProvider($user_id = null)
{
    if (is_null($user_id)) {
        $user_id = \PoP\Root\App::getState('current-user-id');
    }

    $api = PoP_SocialLogin_APIFactory::getInstance();
    return $api->getProvider($user_id);
}

function isSocialloginUser($user_id = null)
{
    $provider = getSocialloginProvider($user_id);
    return $provider != null;
}

function getSocialloginNetworklinks()
{
    $api = PoP_SocialLogin_APIFactory::getInstance();
    return $api->getNetworklinks();
}

// Change the user's display name
\PoP\Root\App::getHookManager()->addAction(
    'popcomponent:sociallogin:usercreated', 
    'userNameUpdated', 
    1
);
