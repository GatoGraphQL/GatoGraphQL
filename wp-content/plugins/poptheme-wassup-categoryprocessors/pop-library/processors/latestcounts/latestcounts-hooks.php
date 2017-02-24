<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Latest Counts Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPThemeWassup_CategoryProcessors_LatestCounts_Hooks {

	function __construct() {

		add_filter(
			'latestcounts:allcontent:classes', 
			array($this, 'get_allcontent_classes')
		);
	}

	function get_allcontent_classes($classes) {

		foreach (PoPTheme_Wassup_CategoryProcessors_ConfigUtils::get_cats(array(POP_CATEGORYPROCESSORS_CONFIGUTILS_WEBPOSTS)) as $section_class) {
			$classes[] = 'post-'.$section_class;
		}

		return $classes;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPThemeWassup_CategoryProcessors_LatestCounts_Hooks();
