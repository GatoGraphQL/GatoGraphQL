<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_WSL_DATALOAD_CHECKPOINT_NONWSLUSER', 'wsl-checkpoint-nonwsluser');

class GD_WSL_Dataload_UserCheckpointProcessor extends GD_Dataload_CheckpointProcessor {

	function get_checkpoints_to_process() {

		return array(
			GD_WSL_DATALOAD_CHECKPOINT_NONWSLUSER,
		);
	}

	function process($checkpoint) {

		switch ($checkpoint) {

			case GD_WSL_DATALOAD_CHECKPOINT_NONWSLUSER:

				if (gd_wsl_is_wsl_user()) {

					return new WP_Error('wsluser');
				}
				break;
		}
	
		return parent::process($checkpoint);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_WSL_Dataload_UserCheckpointProcessor();
