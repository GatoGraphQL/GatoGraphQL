<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WSL_AAL_PoP_Hook_Users extends AAL_Hook_Base {

	public function __construct() {

		// Prompt the user to change the email
		add_action(
			'wsl_hook_process_login_after_wp_insert_user', 
			array($this, 'request_change_email'),
			20, // Execute after the User Welcome Message
			2
		);

		parent::__construct();
	}

	public function request_change_email($user_id, $provider) {

		if (!POP_AAL_USERALIAS_SYSTEMNOTIFICATIONS || !POP_WPAPI_PAGE_EDITPROFILE) {
			return;
		}

		// Twitter's provided user email is a fake one (eg: user_test@example.com), so prompt the user to fix it
		$fake_email_providers = array("Twitter", "Identica", "Tumblr", "Goodreads", "500px", "Vkontakte", "Gowalla", "Steam");
		if (!in_array($provider, $fake_email_providers)) {
			return;
		}

		// $description = sprintf(
		// 	__('%s has not provided your email, so we created a random one for your account: %s. Please change it to your real email, to receive notifications from %s', 'wsl-pop'),
		// 	$provider,
		// 	get_the_author_meta('user_email', $user_id),
		// 	get_bloginfo('name')
		// );
		aal_insert_log( array(
			'action'      => WSL_AAL_POP_ACTION_USER_REQUESTCHANGEEMAIL,
			'object_type' => 'User',
			'user_id'     => POP_AAL_USERALIAS_SYSTEMNOTIFICATIONS,
			'object_id'   => $user_id,
			'object_name' => get_the_author_meta('display_name', $user_id),//$description,
		) );
	}

}