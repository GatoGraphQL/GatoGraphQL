<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class CustomVotingProcessors_DataLoad_FunctionHooks {

    function __construct() {
    
		add_filter(
			'GD_RecommendUnrecommendPost:eligible',
			array($this, 'recommendunrecommendpost_eligible'),
			10,
			2
		);
	}

	function recommendunrecommendpost_eligible($eligible, $post) {

		// If already false, nothing to do
		if (!$eligible) {
			return false;
		}

		// No recommendation for OpinionatedVoteds
		if (gd_get_the_main_category($post->ID) == POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES) {

			return false;
		}

		return $eligible;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new CustomVotingProcessors_DataLoad_FunctionHooks();