<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_SectionProcessors_DataLoad_FunctionHooks {

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
			POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES,
			POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_THOUGHTS,
			POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTS,
			POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS,
			POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_BLOG,
			POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS,
			POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED,
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
new PoPTheme_Wassup_SectionProcessors_DataLoad_FunctionHooks();