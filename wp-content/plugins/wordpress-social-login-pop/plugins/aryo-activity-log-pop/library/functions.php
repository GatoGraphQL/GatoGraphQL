<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Add this library's hooks for AAL
add_action('AAL_PoP_Hooks', 'wsl_aal_pop_hooks');
function wsl_aal_pop_hooks() {

	// Assign them in a global variable, so their methods can be invoked from outside
	global $wsl_aal_pop_hook_user, $wsl_aal_pop_hook_posts;
	$wsl_aal_pop_hook_user = new WSL_AAL_PoP_Hook_User();
	$wsl_aal_pop_hook_posts = new WSL_AAL_PoP_Hook_Posts();
}


/**---------------------------------------------------------------------------------------------------------------
 * Hook into the API: Notification Actions
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('AAL_PoP_API:notifications:userspecific:actions', 'wsl_aal_pop_notification_get_userspecific_actions');
function wsl_aal_pop_notification_get_userspecific_actions($actions) {

	// User-specific Notifications:
	return array_merge(
		$actions,
		array(
			// Notify the user when:
			// Twitter log-in: request user to update the (fake) email
			WSL_AAL_POP_ACTION_USER_REQUESTCHANGEEMAIL,
		)
	);
}