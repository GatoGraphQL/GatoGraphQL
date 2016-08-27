<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class URE_AAL_PoP_Hook_User extends AAL_Hook_Base {

	public function joined_communities($user_id, $form_data, $operationlog) {

		$this->user_joined_communities($user_id, $operationlog['new-communities']);
	}

	public function new_user_communities($user_id, $form_data) {

		$this->user_joined_communities($user_id, $form_data['communities']);
	}

	public function updated_communities($user_id) {

		$this->log_user_action($user_id, URE_AAL_POP_ACTION_USER_UPDATEDCOMMUNITIES);
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

	protected function user_joined_communities($user_id, $communities) {

		if ($communities) {

			foreach ($communities as $community_id) {

				$this->log_community_action($user_id, $community_id, URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY);
			}
		}
	}

	public function community_updated_user_membership($user_id, $community_id) {

		// Before logging this action, delete all previous notifications about the same action, so that they don't appear repeated multiple times
		// This is very annoying since the notification shows the current settings, and not the historical ones, so the notifications are really a repetition
		AAL_Main::instance()->api->delete_user_notifications($community_id, $user_id, array(URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP));

		// Then log the action
		$this->log_community_action($community_id, $user_id, URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP);
	}

	protected function log_community_action($user_id, $object_id, $action) {

		aal_insert_log( array(
			'action'      => $action,
			'object_type' => 'User',
			'user_id'     => $user_id,
			'object_id'   => $object_id,
			'object_name' => get_the_author_meta('display_name', $object_id),
		) );
	}

	public function __construct() {

		add_action('gd_update_mycommunities:update', array($this, 'joined_communities'), 10, 3);
		add_action('gd_custom_createupdate_profile:additionals_create', array($this, 'new_user_communities'), 10, 2);
		add_action('GD_EditMembership:update', array($this, 'community_updated_user_membership'), 10, 2);

		// Updated communities
		add_action('gd_update_mycommunities:update', array($this, 'updated_communities'), 10, 1);

		parent::__construct();
	}

}