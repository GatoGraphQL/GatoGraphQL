<?php

/**---------------------------------------------------------------------------------------------------------------
 * Custom Libraries
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_catname', 'getpop_categoryprocessors_catname', 10, 3);
function getpop_categoryprocessors_catname($name, $cat_id, $format) {

	switch ($cat_id) {

		case GETPOPDEMO_CAT_ARTICLES:
		case GETPOPDEMO_CAT_ANNOUNCEMENTS:
		case GETPOPDEMO_CAT_RESOURCES:

			$plurals = array(
				GETPOPDEMO_CAT_ARTICLES => __('Articles', 'getpop-demo-processors'),
				GETPOPDEMO_CAT_ANNOUNCEMENTS => __('Announcements', 'getpop-demo-processors'), 
				GETPOPDEMO_CAT_RESOURCES => __('Resources', 'getpop-demo-processors'), 
			);
			$singulars = array(
				GETPOPDEMO_CAT_ARTICLES => __('Article', 'getpop-demo-processors'),
				GETPOPDEMO_CAT_ANNOUNCEMENTS => __('Announcement', 'getpop-demo-processors'), 
				GETPOPDEMO_CAT_RESOURCES => __('Resource', 'getpop-demo-processors'), 
			);
			return ($format == 'plural' || $format == 'plural-lc') ? $plurals[$cat_id] : $singulars[$cat_id];
	}

	return $name;
}