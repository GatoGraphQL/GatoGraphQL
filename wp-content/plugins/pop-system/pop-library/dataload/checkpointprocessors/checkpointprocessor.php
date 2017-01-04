<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_SYSTEM_DATALOAD_CHECKPOINT_SYSTEMACCESSKEYVALID', 'system-checkpoint-systemaccesskeyvalid');
define ('POP_SYSTEM_DATALOAD_CHECKPOINT_SYSTEMACCESSIPVALID', 'system-checkpoint-systemaccessipvalid');

class PoPSystem_Dataload_CheckpointProcessor extends GD_Dataload_CheckpointProcessor {

	function get_checkpoints_to_process() {

		return array(
			POP_SYSTEM_DATALOAD_CHECKPOINT_SYSTEMACCESSKEYVALID,
			POP_SYSTEM_DATALOAD_CHECKPOINT_SYSTEMACCESSIPVALID,
		);
	}

	function process($checkpoint) {

		switch ($checkpoint) {

			case POP_SYSTEM_DATALOAD_CHECKPOINT_SYSTEMACCESSKEYVALID:

				// Validate the System Access Key has been defined
				if (!POP_SYSTEM_APIKEYS_SYSTEMACCESS) {
					
					return new WP_Error('systemaccesskeynotdefined');
				}

				// Validate the user has provided the System Access Key as a param in the URL
				$key = $_REQUEST['systemaccesskey'];
				if (!$key) {
					
					return new WP_Error('systemaccesskeyempty');
				}

				// Validate the keys match
				if ($key != POP_SYSTEM_APIKEYS_SYSTEMACCESS) {
					
					return new WP_Error('systemaccesskeyincorrect');
				}
				break;

			case POP_SYSTEM_DATALOAD_CHECKPOINT_SYSTEMACCESSIPVALID:

				// Validate the System Access IPs has been defined
				if (!POP_SYSTEM_IPS_SYSTEMACCESS) {
					
					return new WP_Error('systemaccessipsnotdefined');
				}

				// Validate the user's IP
				$ip = get_client_ip();
				if (!$ip) {
					
					return new WP_Error('systemaccessipempty');
				}

				// Validate the keys match
				if (!in_array($ip, POP_SYSTEM_IPS_SYSTEMACCESS)) {
					
					return new WP_Error('systemaccessipincorrect');
				}
				break;
		}
	
		return parent::process($checkpoint);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPSystem_Dataload_CheckpointProcessor();
