<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('WASSUP_WSL_CHECKPOINT_LOGGEDIN_STATIC', 'wsl-checkpoint-logged-static');

class WSL_SettingsProcessor_CheckpointUtils {

	public static function get_checkpoint($hierarchy, $name) {

		$ret = array();

		$checkpoint = null;

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			$wsl_loggedin_static = array(
				'checkpoints' => array(
					GD_DATALOAD_CHECKPOINT_USERLOGGEDIN_SUBMIT,
					GD_WSL_DATALOAD_CHECKPOINT_NONWSLUSER,
				),
				'type' => GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATIC
			);

			switch ($name) {

				case WASSUP_WSL_CHECKPOINT_LOGGEDIN_STATIC:
					$checkpoint = $wsl_loggedin_static;
					break;
			}
		}
	
		// Allow URE to add the extra checkpoint condition of the user having the Profile role
		$checkpoint = apply_filters('Wassup_SettingsProcessor_CheckpointUtils:checkpoint', $checkpoint, $hierarchy, $name);

		// If there is no checkpoint, then $name failed to find it, which is a bug, so raise an exception
		if (!$checkpoint) {
			throw new Exception(sprintf('No checkpoint found with hierarchy \'%s\' and name \'%s\'', $hierarchy, $name));
		}
	
		return $checkpoint;
	}
}