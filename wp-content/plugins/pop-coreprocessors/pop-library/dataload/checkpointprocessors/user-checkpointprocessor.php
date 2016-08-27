<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_CHECKPOINT_PROFILEACCESS', 'checkpoint-profileaccess');
define ('GD_DATALOAD_CHECKPOINT_PROFILEACCESS_SUBMIT', 'checkpoint-profileaccess-submit');

class PoPCore_Dataload_UserCheckpointProcessor extends GD_Dataload_CheckpointProcessor {

	function get_checkpoints_to_process() {

		return array(
			GD_DATALOAD_CHECKPOINT_PROFILEACCESS,
			GD_DATALOAD_CHECKPOINT_PROFILEACCESS_SUBMIT,
		);
	}

	function process($checkpoint) {

		switch ($checkpoint) {

			case GD_DATALOAD_CHECKPOINT_PROFILEACCESS:

				// Check if the user has Profile Access: access to add/edit content
				if (!user_has_profile_access()) {

					return new WP_Error('usernoprofileaccess');
				}
				break;

			case GD_DATALOAD_CHECKPOINT_PROFILEACCESS_SUBMIT:

				// Check if the user has Profile Access: access to add/edit content
				if (!doing_post() || !user_has_profile_access()) {

					return new WP_Error('usernoprofileaccess');
				}
				break;
		}
	
		return parent::process($checkpoint);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPCore_Dataload_UserCheckpointProcessor();
