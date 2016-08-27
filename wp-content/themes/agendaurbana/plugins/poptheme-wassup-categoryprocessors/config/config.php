<?php

/**---------------------------------------------------------------------------------------------------------------
 * Custom Libraries
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_catname', 'agendaurbana_categoryprocessors_catname', 10, 3);
function agendaurbana_categoryprocessors_catname($name, $cat_id, $format) {

	switch ($cat_id) {

		case AGENDAURBANA_CAT_ARTICLES:
		case AGENDAURBANA_CAT_ANNOUNCEMENTS:
		case AGENDAURBANA_CAT_RESOURCES:
		case AGENDAURBANA_CAT_FEATURED:

			$plurals = array(
				AGENDAURBANA_CAT_ARTICLES => __('Articles', 'agendaurbana'),
				AGENDAURBANA_CAT_ANNOUNCEMENTS => __('Announcements', 'agendaurbana'), 
				AGENDAURBANA_CAT_RESOURCES => __('Resources', 'agendaurbana'), 
				AGENDAURBANA_CAT_FEATURED => __('Featured', 'agendaurbana'), 
			);
			$singulars = array(
				AGENDAURBANA_CAT_ARTICLES => __('Article', 'agendaurbana'),
				AGENDAURBANA_CAT_ANNOUNCEMENTS => __('Announcement', 'agendaurbana'), 
				AGENDAURBANA_CAT_RESOURCES => __('Resource', 'agendaurbana'), 
				AGENDAURBANA_CAT_FEATURED => __('Featured', 'agendaurbana'), 
			);
			return ($format == 'plural' || $format == 'plural-lc') ? $plurals[$cat_id] : $singulars[$cat_id];
	}

	return $name;
}