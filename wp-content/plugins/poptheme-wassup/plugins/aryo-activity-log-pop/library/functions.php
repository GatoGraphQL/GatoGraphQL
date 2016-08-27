<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Hook into the API: Notification Actions
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('AAL_PoP_API:notifications:useractivityposts:actions', 'wassup_aal_pop_notification_get_useractivityposts_actions');
function wassup_aal_pop_notification_get_useractivityposts_actions($actions) {

	// User-activity Notifications:
	return array_merge(
		$actions,
		array(
			// Notify the user when:
			// An extract is created from a post by the user
			AAL_POP_ACTION_POST_REFERENCEDHIGHLIGHTPOST,
		)
	);
}