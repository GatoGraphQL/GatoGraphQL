<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Hook into the API: Notification Actions
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('AAL_PoP_API:notifications:useractivityposts:actions', 'votingprocessors_aal_pop_notification_get_useractivityposts_actions');
function votingprocessors_aal_pop_notification_get_useractivityposts_actions($actions) {

	// User-activity Notifications:
	return array_merge(
		$actions,
		array(
			// Notify the user when:
			// A thought on TPP is created from a post by the user
			AAL_POP_ACTION_POST_REFERENCEDOPINIONATEDVOTEPOST,
		)
	);
}