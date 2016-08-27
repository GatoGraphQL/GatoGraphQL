<?php

/**---------------------------------------------------------------------------------------------------------------
 * Custom Libraries
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_catname', 'mesym_categoryprocessors_catname', 10, 3);
function mesym_categoryprocessors_catname($name, $cat_id, $format) {

	switch ($cat_id) {

		case POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS00:

			$plurals = array(
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS00 => __('Resources', 'mesym'),
			);
			$singulars = array(
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS00 => __('Resource', 'mesym'),
			);
			return ($format == 'plural' || $format == 'plural-lc') ? $plurals[$cat_id] : $singulars[$cat_id];
	}

	return $name;
}