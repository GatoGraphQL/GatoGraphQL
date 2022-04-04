<?php

// Hook the user's network function, adding the users belonging to the same communities as the user
\PoP\Root\App::addFilter('getUserNetworkusers', gdUreGetUserNetworkusers(...), 10, 2);
function gdUreGetUserNetworkusers($usernetwork, $user_id)
{
    if ($communities = gdUreGetCommunitiesStatusActive($user_id)) {
        // Get all the active members of those communities
        foreach ($communities as $community) {
            $community_members = URE_CommunityUtils::getCommunityMembers($community);
            $usernetwork = array_merge(
                $usernetwork,
                $community_members
            );
        }

        // Remove duplicates
        $usernetwork = array_unique($usernetwork);

        // Remove the user him/herself (since the user is also a member of his/her communities)
        $pos = array_search($user_id, $usernetwork);
        if ($pos > -1) {
            array_splice($usernetwork, $pos, 1);
        }
    }

    return $usernetwork;
}
