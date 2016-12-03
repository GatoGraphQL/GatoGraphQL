<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_catname', 'agendaurbana_sectionprocessors_catname', 10, 3);
function agendaurbana_sectionprocessors_catname($name, $cat_id, $format) {

	switch ($cat_id) {

		case POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS:

			$plurals = array(
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS => __('Social Conflicts', 'agendaurbana'), 
			);
			$singulars = array(
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS => __('Social Conflict', 'agendaurbana'), 
			);
			return ($format == 'plural' || $format == 'plural-lc') ? $plurals[$cat_id] : $singulars[$cat_id];
	}

	return $name;
}

/**---------------------------------------------------------------------------------------------------------------
 * Override with custom blocks
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_Template_Processor_CustomBlockGroups:blocks:whoweare', 'agendaurbana_custom_blocks_whoweare', 100);
function agendaurbana_custom_blocks_whoweare($blocks) {

	return array(
		GD_TEMPLATE_BLOCK_WHOWEARE_CORE_SCROLL,
		GD_TEMPLATE_BLOCK_WHOWEARE_VOLUNTEERS_SCROLL,
	);
}