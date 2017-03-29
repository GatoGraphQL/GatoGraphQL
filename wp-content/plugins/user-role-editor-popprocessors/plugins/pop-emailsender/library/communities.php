<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Communities functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Hook the email notifications, adding the community members to the users' network
add_filter('PoP_EmailSender_Hooks:networkusers', 'pop_ure_emailsender_get_user_networkusers', 10, 2);
function pop_ure_emailsender_get_user_networkusers($usernetwork, $user_id) {

	if (gd_ure_is_community($user_id)) {

		$community_members = URE_CommunityUtils::get_community_members($user_id);
		$usernetwork = array_merge(
			$usernetwork,
			$community_members
		);

		// Remove duplicates
		$usernetwork = array_unique($usernetwork);
	}

	return $usernetwork;
}
