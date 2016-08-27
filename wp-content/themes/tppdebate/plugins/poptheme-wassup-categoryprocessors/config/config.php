<?php

/**---------------------------------------------------------------------------------------------------------------
 * Custom Libraries
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_catname', 'tppdebate_categoryprocessors_catname', 10, 3);
function tppdebate_categoryprocessors_catname($name, $cat_id, $format) {

	switch ($cat_id) {

		case TPPDEBATE_CAT_ARTICLES:
		case TPPDEBATE_CAT_ANNOUNCEMENTS:
		case TPPDEBATE_CAT_RESOURCES:
		case TPPDEBATE_CAT_FEATURED:
		case TPPDEBATE_CAT_LEGAL:

			$plurals = array(
				TPPDEBATE_CAT_ARTICLES => __('Articles', 'agendaurbana'),
				TPPDEBATE_CAT_ANNOUNCEMENTS => __('Announcements', 'agendaurbana'), 
				TPPDEBATE_CAT_RESOURCES => __('Resources', 'agendaurbana'), 
				TPPDEBATE_CAT_FEATURED => __('Featured', 'agendaurbana'), 
				TPPDEBATE_CAT_LEGAL => __('Legal', 'agendaurbana'), 
			);
			$singulars = array(
				TPPDEBATE_CAT_ARTICLES => __('Article', 'agendaurbana'),
				TPPDEBATE_CAT_ANNOUNCEMENTS => __('Announcement', 'agendaurbana'), 
				TPPDEBATE_CAT_RESOURCES => __('Resource', 'agendaurbana'), 
				TPPDEBATE_CAT_FEATURED => __('Featured', 'agendaurbana'), 
				TPPDEBATE_CAT_LEGAL => __('Legal', 'agendaurbana'), 
			);
			return ($format == 'plural' || $format == 'plural-lc') ? $plurals[$cat_id] : $singulars[$cat_id];

		// case POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS00:
		// case POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS01:

		// 	$plurals = array(
		// 		POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS00 => __('Resources', 'tppdebate'),
		// 		POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS01 => __('Legal', 'tppdebate'), 
		// 	);
		// 	$singulars = array(
		// 		POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS00 => __('Resource', 'tppdebate'),
		// 		POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS01 => __('Legal', 'tppdebate'), 
		// 	);
		// 	return ($format == 'plural' || $format == 'plural-lc') ? $plurals[$cat_id] : $singulars[$cat_id];
	}

	return $name;
}