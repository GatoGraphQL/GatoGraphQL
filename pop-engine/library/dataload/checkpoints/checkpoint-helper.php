<?php
namespace PoP\Engine\Impl;

define ('POPENGINE_CHECKPOINTCONFIGURATION_ACTIONPATHISMODULE', 'actionpathismodule');
define ('POPENGINE_CHECKPOINTCONFIGURATION_ACTIONPATHISMODULE_POST', 'actionpathismodule-post');

class CheckpointUtils extends \PoP\Engine\CheckpointHelperBase {

	public static function get_checkpoint_configuration($name) {

		// Override the checkpoints from poptheme-wassup: whenever the user logged in checkpoint is requested,
		// add the further addition of checking that it is a profile
		switch ($name) {

			case POPENGINE_CHECKPOINTCONFIGURATION_ACTIONPATHISMODULE:
				return array(
					'checkpoints' => array(
						GD_DATALOAD_CHECKPOINT_ACTIONPATHISMODULE,
					),
					'type' => GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATELESS
				);

			case POPENGINE_CHECKPOINTCONFIGURATION_ACTIONPATHISMODULE_POST:
				return array(
					'checkpoints' => array(
						GD_DATALOAD_CHECKPOINT_ACTIONPATHISMODULE,
						GD_DATALOAD_CHECKPOINT_DOINGPOST,
					),
					'type' => GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATELESS,
				);
		}
	
		return parent::get_checkpoint_configuration($name);
	}
}