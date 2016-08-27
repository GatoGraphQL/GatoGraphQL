<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class Wassup_EM_DataLoad_FunctionHooks {

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

		// No up/down-vote for Events
		if (get_post_type($post->ID) == EM_POST_TYPE_EVENT) {

			return false;
		}

		return $eligible;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_EM_DataLoad_FunctionHooks();