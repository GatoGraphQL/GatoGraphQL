<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_OrganikProcessors_DataLoad_FunctionHooks {

    function __construct() {
    
		add_filter(
			'GD_UpdownvoteUndoUpdownvotePost:eligible',
			array($this, 'upvoteundoupvotepost_eligible'),
			10,
			2
		);
	}

	function upvoteundoupvotepost_eligible($eligible, $post) {

		// If already false, nothing to do
		if (!$eligible) {
			return false;
		}

		// No up/down-vote for any of the categories here
		$cats = array_filter(array(
			POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMS,
		));
		if (in_array(gd_get_the_main_category($post->ID), $cats)) {

			return false;
		}

		return $eligible;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_OrganikProcessors_DataLoad_FunctionHooks();