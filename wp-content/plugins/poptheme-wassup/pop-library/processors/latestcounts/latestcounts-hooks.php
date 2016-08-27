<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Latest Counts Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPThemeWassup_LatestCounts_Hooks {

	function __construct() {

		add_filter(
			'latestcounts:allcontent:classes', 
			array($this, 'get_allcontent_classes')
		);
	}

	function get_allcontent_classes($classes) {

		$section_classes = array_filter(
			array(
				POPTHEME_WASSUP_CAT_WEBPOSTLINKS,
				POPTHEME_WASSUP_CAT_WEBPOSTS,
			)
		);
		foreach ($section_classes as $section_class) {
			$classes[] = 'post-'.$section_class;
		}

		return $classes;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPThemeWassup_LatestCounts_Hooks();
