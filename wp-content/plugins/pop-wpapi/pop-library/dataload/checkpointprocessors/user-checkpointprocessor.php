<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Always true checkpoint: it's used to pretend we have a checkpoint, but actually we don't need it. Use to make the system retrieve the logged-in information
// define ('GD_DATALOAD_CHECKPOINT_ALWAYSTRUE', 'checkpoint-alwaystrue');
define ('GD_DATALOAD_CHECKPOINT_USERLOGGEDIN', 'checkpoint-userloggedin');
define ('GD_DATALOAD_CHECKPOINT_USERLOGGEDIN_SUBMIT', 'checkpoint-userloggedin-submit');
define ('GD_DATALOAD_CHECKPOINT_USERNOTLOGGEDIN_SUBMIT', 'checkpoint-usernotloggedin-submit');
define ('GD_DATALOAD_CHECKPOINT_USERCANEDIT', 'checkpoint-usercanedit');
define ('GD_DATALOAD_CHECKPOINT_NONCE', 'checkpoint-nonce');
define ('GD_DATALOAD_CHECKPOINT_ISADMINISTRATOR', 'checkpoint-isadministrator');

class GD_Dataload_UserCheckpointProcessor extends GD_Dataload_CheckpointProcessor {

	function get_checkpoints_to_process() {

		return array(
			// GD_DATALOAD_CHECKPOINT_ALWAYSTRUE,
			GD_DATALOAD_CHECKPOINT_USERLOGGEDIN,
			GD_DATALOAD_CHECKPOINT_USERLOGGEDIN_SUBMIT,
			GD_DATALOAD_CHECKPOINT_USERNOTLOGGEDIN_SUBMIT,
			GD_DATALOAD_CHECKPOINT_USERCANEDIT,
			GD_DATALOAD_CHECKPOINT_NONCE,
			GD_DATALOAD_CHECKPOINT_ISADMINISTRATOR,
		);
	}

	function process($checkpoint) {

		$vars = GD_TemplateManager_Utils::get_vars();
		switch ($checkpoint) {

			case GD_DATALOAD_CHECKPOINT_USERLOGGEDIN:

				// Check if the user is logged in
				if (!$vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/) {

					return new WP_Error('usernotloggedin');
				}
				break;

			case GD_DATALOAD_CHECKPOINT_USERLOGGEDIN_SUBMIT:

				// Check if the user is logged in
				if (!doing_post() || !$vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/) {

					return new WP_Error('usernotloggedin');
				}
				break;

			case GD_DATALOAD_CHECKPOINT_USERNOTLOGGEDIN_SUBMIT:

				// Check if the user is not logged in
				if (!doing_post() || $vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/) {

					return new WP_Error('userloggedin');
				}	
				break;

			case GD_DATALOAD_CHECKPOINT_USERCANEDIT:

				// Check if the user can edit the specific post
				$post_id = $_REQUEST['pid'];
				if (!gd_current_user_can_edit($post_id)) {
					
					return new WP_Error('usercannotedit');
				}
				break;

			case GD_DATALOAD_CHECKPOINT_NONCE:

				// Check if the user can edit the specific post
				$post_id = $_REQUEST['pid'];
				$nonce = $_REQUEST['_wpnonce'];
				if (!gd_verify_nonce( $nonce, GD_NONCE_EDITURL, $post_id)) {

					return new WP_Error('nonceinvalid');
				}
				break;

			case GD_DATALOAD_CHECKPOINT_ISADMINISTRATOR:

				if (!has_role('administrator')) {
					
					return new WP_Error('userisnotadmin');
				}
				break;
		}
	
		return parent::process($checkpoint);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Dataload_UserCheckpointProcessor();
