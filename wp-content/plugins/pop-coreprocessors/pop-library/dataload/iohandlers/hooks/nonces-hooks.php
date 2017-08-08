<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_Core_DataLoad_NoncesCheckpointIOHandler_Hooks {

	function __construct() {

		add_filter(
			'GD_DataLoad_CheckpointIOHandler:feedback', 
			array($this, 'modify_feedback'),
			10,
			7
		);
	}

	function modify_feedback($feedback, $checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {

		// Get the user info? (used for pages where user logged in is needed. Generally same as with checkpoints)
		if ($iohandler_atts[GD_DATALOAD_GETUSERINFO]) {
			
			// Nonces for validation for the Media Manager
			if (!$feedback[GD_URLPARAM_NONCES]) {
				$feedback[GD_URLPARAM_NONCES] = array();
			}
			$nonces = array(
				'media-form',
				'media-send-to-editor',
			);
			foreach ($nonces as $nonce) {
				$feedback[GD_URLPARAM_NONCES][$nonce] = wp_create_nonce($nonce);
			}
		}
		
		return $feedback;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Core_DataLoad_NoncesCheckpointIOHandler_Hooks();
