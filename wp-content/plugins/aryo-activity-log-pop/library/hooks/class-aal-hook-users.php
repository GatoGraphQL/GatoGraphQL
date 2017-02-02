<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



class AAL_PoP_Hook_Users extends AAL_Hook_Base {

	public function __construct() {

		// Welcome message given to user upon creating an account
		add_action('gd_createupdate_user:additionals_create', array($this, 'welcome_message'));

		// Follows/Unfollows user
		add_action('gd_followuser', array($this, 'follows_user'));
		add_action('gd_unfollowuser', array($this, 'unfollows_user'));

		// Changed password
		add_action('gd_changepassword_user', array($this, 'changed_password'), 10, 1);

		// Updated profile
		add_action('gd_createupdate_user:additionals_update', array($this, 'updated_profile'), 10, 1);

		// Updated photo
		add_action('gd_useravatar_update:additionals', array($this, 'updated_photo'), 10, 1);

		// Logged in/out
		add_action('gd:user:loggedin', array($this, 'logged_in'), 10, 1);
		add_action('gd:user:loggedout', array($this, 'logged_out'), 10, 1);

		// When a user is deleted from the system, delete all notifications from/for the user
		add_action('delete_user', array($this, 'delete_user'), 10, 1);

		parent::__construct();
	}

	public function welcome_message($user_id) {

		// Enable only if the System User and the Welcome Message Post have been defined
		if (!POP_AAL_USERALIAS_SYSTEMNOTIFICATIONS || !POP_AAL_PAGEALIAS_USERWELCOME) {
			return;
		}

		aal_insert_log( array(
			'action'      => AAL_POP_ACTION_USER_WELCOMENEWUSER,
			'object_type' => 'User',
			'user_id'     => POP_AAL_USERALIAS_SYSTEMNOTIFICATIONS,
			'object_id'   => $user_id,
			'object_name' => get_the_author_meta('display_name', $user_id),
		) );
	}

	public function follows_user($followed_user_id) {

		$this->followunfollows_user($followed_user_id, AAL_POP_ACTION_USER_FOLLOWSUSER);
	}

	public function unfollows_user($unfollowed_user_id) {

		$this->followunfollows_user($unfollowed_user_id, AAL_POP_ACTION_USER_UNFOLLOWSUSER);
	}

	public function followunfollows_user($user_id, $action) {

		aal_insert_log( array(
			'action'      => $action,
			'object_type' => 'User',
			'user_id'     => get_current_user_id(),
			'object_id'   => $user_id,
			'object_name' => get_the_author_meta('display_name', $user_id),
		) );
	}

	public function changed_password($user_id) {

		$this->log_user_action($user_id, AAL_POP_ACTION_USER_CHANGEDPASSWORD);
	}

	public function updated_profile($user_id) {

		$this->log_user_action($user_id, AAL_POP_ACTION_USER_UPDATEDPROFILE);
	}

	public function updated_photo($user_id) {

		$this->log_user_action($user_id, AAL_POP_ACTION_USER_UPDATEDPHOTO);
	}

	public function logged_in($user_id) {

		$this->log_user_action($user_id, AAL_POP_ACTION_USER_LOGGEDIN);
	}

	public function logged_out($user_id) {

		$this->log_user_action($user_id, AAL_POP_ACTION_USER_LOGGEDOUT);
	}

	protected function log_user_action($user_id, $action) {

		aal_insert_log( array(
			'action'      => $action,
			'object_type' => 'User',
			'user_id'     => $user_id,
			'object_id'   => $user_id,
			'object_name' => get_the_author_meta('display_name', $user_id),
		) );
	}

	public function delete_user($user_id) {

		AAL_Main::instance()->api->clear_user($user_id);
	}

}