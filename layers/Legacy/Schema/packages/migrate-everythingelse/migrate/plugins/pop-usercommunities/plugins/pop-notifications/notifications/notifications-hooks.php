<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class URE_PoP_Notifications_NotificationHooks
{
    public function __construct()
    {

        // Hook into the API: User Network Meta keys
        \PoP\Root\App::getHookManager()->addFilter(
            'AAL_PoP_API:notifications:usernetwork:metakeys',
            array($this, 'getUsernetworkMetakeys')
        );

        // Hook into the API: User Network Conditions
        \PoP\Root\App::getHookManager()->addFilter(
            'AAL_PoP_API:notifications:usernetwork:conditions',
            array($this, 'getUsernetworkConditions'),
            10,
            2
        );

        // Hook into the API: Notification Actions
        \PoP\Root\App::getHookManager()->addFilter(
            'AAL_PoP_API:notifications:userplusnetwork-user:actions',
            array($this, 'getUserplusnetworkActions')
        );

        \PoP\Root\App::getHookManager()->addFilter(
            'AAL_PoP_API:notifications:useristarget:actions',
            array($this, 'getUseristargetActions')
        );

        \PoP\Root\App::getHookManager()->addFilter(
            'AAL_PoP_API:additional_notifications:markasread:users:actions',
            array($this, 'addUseractions')
        );
        \PoP\Root\App::getHookManager()->addFilter(
            'AAL_PoP_API:additional_notifications:markasread:users:actions',
            array($this, 'addSameuseractions')
        );
    }

    public function getUsernetworkMetakeys($metakeys)
    {
        return array_merge(
            $metakeys,
            array(
                // Add the Community to the user network
                GD_URE_METAKEY_PROFILE_COMMUNITIES,
            )
        );
    }

    public function getUsernetworkConditions($user_network_conditions, $user_id)
    {
        global $wpdb;

        // Get all the communities from the user, where it has been accepted as a member
        if ($communities = gdUreGetCommunitiesStatusActive($user_id)) {
            // Iterate all the communities and get all their members
            $communitymembers = array();
            foreach ($communities as $community) {
                $communitymembers = array_merge(
                    $communitymembers,
                    URE_CommunityUtils::getCommunityMembers($community)
                );
            }

            // Add these members activities also for the notifications
            if ($communitymembers) {
                $user_network_conditions[] = sprintf(
                    '
						%2$s.user_id in (
							%1$s
						)
					',
                    implode(', ', $communitymembers),
                    $wpdb->pop_notifications
                );
            }
        }

        return $user_network_conditions;
    }

    public function getUserplusnetworkActions($actions)
    {

        // User + Network Notifications:
        return array_merge(
            $actions,
            array(
                // Notify the user when:
                // - Someone from the network joins a community
                // - Anyone joined the user (user = community)
                URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY,
            )
        );
    }

    public function getUseristargetActions($actions)
    {

        // The User is the target of the action Notifications:
        return array_merge(
            $actions,
            array(
                // Notify the user when:
                // - By Hook: A community updates the membership of the user
                URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP,
            )
        );
    }

    public function addUseractions($actions)
    {
        $actions[] = URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY;
        return $actions;
    }

    public function addSameuseractions($actions)
    {
        $actions[] = URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP;
        return $actions;
    }
}

/**
 * Initialize
 */
new URE_PoP_Notifications_NotificationHooks();
