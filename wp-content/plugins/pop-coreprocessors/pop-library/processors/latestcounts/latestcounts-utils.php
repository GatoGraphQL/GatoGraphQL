<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_LatestCounts_Utils {

	public static function author_filters($classes, $template_id, $atts) {

		// Allow URE to add Organization members
		return apply_filters('latestcounts:author:classes', $classes, $template_id, $atts);
	}

	public static function get_allcontent_classes($template_id, $atts) {

		return apply_filters('latestcounts:allcontent:classes', array(), $template_id, $atts);
	}
}