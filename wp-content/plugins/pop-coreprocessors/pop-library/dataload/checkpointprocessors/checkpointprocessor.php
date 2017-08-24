<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_CHECKPOINT_PROFILEACCESS', 'checkpoint-profileaccess');
define ('GD_DATALOAD_CHECKPOINT_PROFILEACCESS_SUBMIT', 'checkpoint-profileaccess-submit');
// define ('GD_DATALOAD_CHECKPOINT_DOMAINVALID', 'checkpoint-domainvalid');

class PoPCore_Dataload_CheckpointProcessor extends GD_Dataload_CheckpointProcessor {

	function get_checkpoints_to_process() {

		return array(
			GD_DATALOAD_CHECKPOINT_PROFILEACCESS,
			GD_DATALOAD_CHECKPOINT_PROFILEACCESS_SUBMIT,
			// GD_DATALOAD_CHECKPOINT_DOMAINVALID,
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

			// case GD_DATALOAD_CHECKPOINT_DOMAINVALID:

			// 	// Check if the domain passed in param 'domain' is allowed
			// 	$domain = GD_Template_Processor_DomainUtils::get_domain_from_request();
			// 	if (!$domain) {

			// 		return new WP_Error('domainempty');
			// 	}
			// 	$allowed_domains = PoP_Frontend_ConfigurationUtils::get_allowed_domains();
			// 	if (!in_array($domain, $allowed_domains)) {

			// 		return new WP_Error('domainnotvalid');
			// 	}
			// 	break;
		}
	
		return parent::process($checkpoint);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPCore_Dataload_CheckpointProcessor();
