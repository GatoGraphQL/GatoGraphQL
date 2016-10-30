<?php

/**---------------------------------------------------------------------------------------------------------------
 * Custom Libraries
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_catname', 'getpop_categoryprocessors_catname', 10, 3);
function getpop_categoryprocessors_catname($name, $cat_id, $format) {

	switch ($cat_id) {

		case POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS00:

			$plurals = array(
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS00 => __('Resources', 'getpop'),
			);
			$singulars = array(
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS00 => __('Resource', 'getpop'),
			);
			return ($format == 'plural' || $format == 'plural-lc') ? $plurals[$cat_id] : $singulars[$cat_id];
	}

	return $name;
}