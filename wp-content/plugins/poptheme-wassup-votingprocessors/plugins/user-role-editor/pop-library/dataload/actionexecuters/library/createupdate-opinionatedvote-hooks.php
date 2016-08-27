<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class VotingProcessors_DataLoad_CreateUpdateOpinionatedVotedHooks {

    function __construct() {
    
		add_filter(
			'GD_CreateUpdate_OpinionatedVoted:createadditionals',
			array($this, 'createadditionals')
		);
	}

	function createadditionals($post_id) {
		
		// Redundancy on who has created the OpinionatedVoted: an individual or an organization
		// This way we can show the slider in the Homepage "Latest thoughts about TPP" and split them into "By people" / "By organizations"
		// This works because the OpinionatedVoted has only 1 author
		GD_MetaManager::add_post_meta($post_id, GD_URE_METAKEY_POST_AUTHORROLE, gd_ure_get_the_main_userrole(get_current_user_id()), true);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_DataLoad_CreateUpdateOpinionatedVotedHooks();