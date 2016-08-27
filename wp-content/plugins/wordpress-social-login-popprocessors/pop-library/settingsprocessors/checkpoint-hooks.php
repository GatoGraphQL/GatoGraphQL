<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class WSL_SettingsProcessor_CheckpointHooks {

	function __construct() {

		add_filter(
			'Wassup_Template_SettingsProcessor:checkpoints',
			array($this, 'get_changepwd_checkpoints'),
			10,
			3
		);
	}

	function get_changepwd_checkpoints($checkpoint, $hierarchy, $name) {

		switch ($name) {

			case POP_WPAPI_PAGE_CHANGEPASSWORDPROFILE:
				
				// Change the checkpoint: non-WSL users cannot edit their pwd
				return WSL_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_WSL_CHECKPOINT_LOGGEDIN_STATIC);
		}

		return $checkpoint;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new WSL_SettingsProcessor_CheckpointHooks();
