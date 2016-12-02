<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Add this library's hooks for AAL
add_action('AAL_PoP_Hooks', 'ure_aal_pop_hooks');
function ure_aal_pop_hooks() {

	// Assign them in a global variable, so their methods can be invoked from outside
	global $ure_aal_pop_hook_user;
	$ure_aal_pop_hook_user = new URE_AAL_PoP_Hook_User();
}

/**---------------------------------------------------------------------------------------------------------------
 * AAL Dashboard options
 * ---------------------------------------------------------------------------------------------------------------*/
// Settings in file aryo-activity-log/classes/class-aal-activity-log-list-table.php
add_filter('pop_aal_init_caps', 'ure_aal_init_caps');
function ure_aal_init_caps($caps_settings) {

	foreach ($caps_settings as $role => $cap_settings) {
		$caps_settings[$role][] = GD_URE_ROLE_INDIVIDUAL;
		$caps_settings[$role][] = GD_URE_ROLE_ORGANIZATION;
	}

	return $caps_settings;
}

/**---------------------------------------------------------------------------------------------------------------
 * Hook into the API: User Network Meta keys
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('AAL_PoP_API:notifications:usernetwork:metakeys', 'ure_aal_pop_notification_get_usernetwork_metakeys');
function ure_aal_pop_notification_get_usernetwork_metakeys($metakeys) {

	return array_merge(
		$metakeys,
		array(
			// Add the Community to the user network
			GD_URE_METAKEY_PROFILE_COMMUNITIES,
		)
	);
}

/**---------------------------------------------------------------------------------------------------------------
 * Hook into the API: User Network Conditions
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('AAL_PoP_API:notifications:usernetwork:conditions', 'ure_aal_pop_notification_get_usernetwork_conditions', 10, 2);
function ure_aal_pop_notification_get_usernetwork_conditions($user_network_conditions, $user_id) {

	global $wpdb;

	// Get all the communities from the user, where it has been accepted as a member
	if ($communities = gd_ure_get_communities_status_active($user_id)) {
		
		// Iterate all the communities and get all their members
		$communitymembers = array();
		foreach ($communities as $community) {
			
			$communitymembers = array_merge(
				$communitymembers,
				URE_CommunityUtils::get_community_members($community)
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
				$wpdb->activity_log
			);
		}
	}

	return $user_network_conditions;
}

/**---------------------------------------------------------------------------------------------------------------
 * Hook into the API: Notification Actions
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('AAL_PoP_API:notifications:userplusnetwork:actions', 'ure_aal_pop_notification_get_userplusnetwork_actions');
function ure_aal_pop_notification_get_userplusnetwork_actions($actions) {

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

add_filter('AAL_PoP_API:notifications:useristarget:actions', 'ure_aal_pop_notification_get_useristarget_actions');
function ure_aal_pop_notification_get_useristarget_actions($actions) {

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