<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class Wassup_DataLoad_FunctionHooks {

    function __construct() {
    
		add_filter(
			'GD_UpdownvoteUndoUpdownvotePost:eligible',
			array($this, 'upvoteundoupvotepost_eligible'),
			10,
			2
		);

		add_filter(
			'GD_RecommendUnrecommendPost:eligible',
			array($this, 'recommendunrecommendpost_eligible'),
			10,
			2
		);
	}

	function upvoteundoupvotepost_eligible($eligible, $post) {

		// If already false, nothing to do
		if (!$eligible) {
			return false;
		}

		// No up/down-vote for Links, WebPosts
		$skip_cats = array(
			// POPTHEME_WASSUP_CAT_WEBPOSTLINKS,
			POPTHEME_WASSUP_CAT_WEBPOSTS,
		);
		if (in_array(gd_get_the_main_category($post->ID), $skip_cats)) {

			return false;
		}

		return $eligible;
	}

	function recommendunrecommendpost_eligible($eligible, $post) {

		// If already false, nothing to do
		if (!$eligible) {
			return false;
		}

		// No recommendation for Highlights
		$skip_cats = array(
			POPTHEME_WASSUP_CAT_HIGHLIGHTS,
		);
		if (in_array(gd_get_the_main_category($post->ID), $skip_cats)) {

			return false;
		}

		return $eligible;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_DataLoad_FunctionHooks();