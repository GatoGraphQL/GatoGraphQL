<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_DATALOAD_CHECKPOINT_ISPROFILEORGANIZATION', 'ure-checkpoint-isprofileorganization');
define ('GD_URE_DATALOAD_CHECKPOINT_ISPROFILEINDIVIDUAL', 'ure-checkpoint-isprofileindividual');
define ('GD_URE_DATALOAD_CHECKPOINT_ISCOMMUNITY', 'ure-checkpoint-iscommunity');
define ('GD_URE_DATALOAD_CHECKPOINT_EDITINGCOMMUNITYMEMBER', 'ure-checkpoint-editingcommunitymember');

class GD_URE_Dataload_UserCheckpointProcessor extends GD_Dataload_CheckpointProcessor {

	function get_checkpoints_to_process() {

		return array(
			GD_URE_DATALOAD_CHECKPOINT_ISPROFILEORGANIZATION,
			GD_URE_DATALOAD_CHECKPOINT_ISPROFILEINDIVIDUAL,
			GD_URE_DATALOAD_CHECKPOINT_ISCOMMUNITY,
			GD_URE_DATALOAD_CHECKPOINT_EDITINGCOMMUNITYMEMBER,
		);
	}

	function process($checkpoint) {

		switch ($checkpoint) {

			case GD_URE_DATALOAD_CHECKPOINT_ISPROFILEORGANIZATION:

				if (!gd_ure_is_organization()) {

					return new WP_Error('profilenotorganization');
				}
				break;

			case GD_URE_DATALOAD_CHECKPOINT_ISPROFILEINDIVIDUAL:

				if (!gd_ure_is_individual()) {

					return new WP_Error('profilenotindividual');
				}
				break;

			case GD_URE_DATALOAD_CHECKPOINT_ISCOMMUNITY:

				if (!gd_ure_is_community()) {

					return new WP_Error('profilenotcommunity');
				}
				break;

			case GD_URE_DATALOAD_CHECKPOINT_EDITINGCOMMUNITYMEMBER:

				// Validate the user being edited is member of the community
				$user_id = $_REQUEST['uid'];
				$community = get_current_user_id();
				$status = GD_MetaManager::get_user_meta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS);
				$community_status = gd_ure_find_community_metavalues($community, $status);

				if (empty($community_status)) {

					return new WP_Error('editingnotcommunitymember');
				}
				break;
		}
	
		return parent::process($checkpoint);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Dataload_UserCheckpointProcessor();
