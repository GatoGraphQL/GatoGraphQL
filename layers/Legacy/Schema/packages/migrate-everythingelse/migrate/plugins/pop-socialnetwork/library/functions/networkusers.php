<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * user followers and network
 */

function getUserFollowers($user_id)
{
    if ($followers = \PoPSchema\UserMeta\Utils::getUserMeta($user_id, GD_METAKEY_PROFILE_FOLLOWEDBY)) {
        return $followers;
    }

    return array();
}
function getUserNetworkusers($user_id)
{

    // Allow URE to override with the same-community users
    return \PoP\Root\App::getHookManager()->applyFilters(
        'getUserNetworkusers',
        getUserFollowers($user_id),
        $user_id
    );
}
