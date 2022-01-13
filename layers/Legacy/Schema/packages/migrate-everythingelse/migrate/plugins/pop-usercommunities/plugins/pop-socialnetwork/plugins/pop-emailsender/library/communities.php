<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// Hook the email notifications, adding the community members to the users' network
HooksAPIFacade::getInstance()->addFilter('PoP_EmailSender_Hooks:networkusers', 'popUreEmailsenderGetUserNetworkusers', 10, 2);
function popUreEmailsenderGetUserNetworkusers($usernetwork, $user_id)
{
    if (gdUreIsCommunity($user_id)) {
        $community_members = URE_CommunityUtils::getCommunityMembers($user_id);
        $usernetwork = array_merge(
            $usernetwork,
            $community_members
        );

        // Remove duplicates
        $usernetwork = array_unique($usernetwork);
    }

    return $usernetwork;
}
