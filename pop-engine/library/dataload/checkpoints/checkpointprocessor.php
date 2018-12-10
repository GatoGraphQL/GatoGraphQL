<?php

define ('GD_DATALOAD_CHECKPOINT_ACTIONPATHISMODULE', 'actionpath-is-module');
define ('GD_DATALOAD_CHECKPOINT_DOINGPOST', 'doing-post');

class PoP_Engine_Dataload_CheckpointProcessor extends GD_Dataload_CheckpointProcessor {

	function get_checkpoints_to_process() {

		return array(
			GD_DATALOAD_CHECKPOINT_ACTIONPATHISMODULE,
			GD_DATALOAD_CHECKPOINT_DOINGPOST,
		);
	}

	function process($checkpoint, $module = null) {

		$vars = PoP_ModuleManager_Vars::get_vars();
		switch ($checkpoint) {

			case GD_DATALOAD_CHECKPOINT_ACTIONPATHISMODULE:

				// Check if the user is logged in
				if ($vars['actionpath'] != PoP_ModulePathManager_Utils::get_stringified_module_propagation_current_path($module)) {

					return new WP_Error('actionpathisnotmodule');
				}
				break;

			case GD_DATALOAD_CHECKPOINT_DOINGPOST:

				// Check if the user is logged in
				if (!doing_post()) {

					return new WP_Error('notdoingpost');
				}
				break;
		}
	
		return parent::process($checkpoint, $module);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Engine_Dataload_CheckpointProcessor();
