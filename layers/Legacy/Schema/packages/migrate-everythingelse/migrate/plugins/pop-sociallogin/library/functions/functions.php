<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

function getSocialloginProvider($user_id = null)
{
    if (is_null($user_id)) {
        $vars = ApplicationState::getVars();
        $user_id = $vars['global-userstate']['current-user-id'];
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
HooksAPIFacade::getInstance()->addAction(
    'popcomponent:sociallogin:usercreated', 
    'userNameUpdated', 
    1
);
