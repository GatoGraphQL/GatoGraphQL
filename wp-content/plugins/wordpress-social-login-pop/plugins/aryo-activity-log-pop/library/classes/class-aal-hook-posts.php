<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WSL_AAL_PoP_Hook_Posts extends AAL_Hook_Base {

	public function welcome_message($user_id) {

		global $aal_pop_hook_user;
		$aal_pop_hook_user->welcome_message($user_id);
	}

	public function __construct() {

		// User welcome message (function implemented already, but must connect it with the hook)
		add_action(
			'wsl_hook_process_login_after_wp_insert_user', 
			array($this, 'welcome_message')
		);

		parent::__construct();
	}

}