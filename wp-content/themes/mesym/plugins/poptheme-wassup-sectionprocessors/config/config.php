<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_catname', 'mesym_sectionprocessors_catname', 10, 3);
function mesym_sectionprocessors_catname($name, $cat_id, $format) {

	switch ($cat_id) {

		case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS:

			$plurals = array(
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS => __('Projects', 'mesym'), 
			);
			$singulars = array(
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS => __('Project', 'mesym'), 
			);
			return ($format == 'plural' || $format == 'plural-lc') ? $plurals[$cat_id] : $singulars[$cat_id];
	}

	return $name;
}

/**---------------------------------------------------------------------------------------------------------------
 * Override with custom blocks
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_CustomBlockGroups:blocks:whoweare', 'mesym_blocks_whoweare', 100);
function mesym_blocks_whoweare($blocks) {

	return array(
		GD_TEMPLATE_BLOCK_WHOWEARE_CORE_SCROLL,
		GD_TEMPLATE_BLOCK_WHOWEARE_VOLUNTEERS_SCROLL,
		GD_TEMPLATE_BLOCK_WHOWEARE_ADVISORS_SCROLL,
	);
}