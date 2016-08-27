<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class Wassup_URE_SettingsProcessor_CheckpointHooks {

	function __construct() {

		add_filter(
			'Wassup_SettingsProcessor_CheckpointUtils:checkpoint',
			array($this, 'override_checkpoints'),
			10,
			3
		);
	}

	function override_checkpoints($checkpoint, $hierarchy, $name) {

		// Add the checkpoint condition of verifying that the user has Profile role
		switch ($name) {

			case WASSUP_CHECKPOINT_LOGGEDIN_STATIC:
				return Wassup_URE_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_URE_CHECKPOINT_LOGGEDINPROFILE_STATIC);
			
			case WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER:
				return Wassup_URE_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_URE_CHECKPOINT_LOGGEDINPROFILE_DATAFROMSERVER);

			case WASSUP_CHECKPOINT_LOGGEDIN_CANEDIT:
				return Wassup_URE_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_URE_CHECKPOINT_LOGGEDINPROFILE_CANEDIT);
		}

		return $checkpoint;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_URE_SettingsProcessor_CheckpointHooks();
