<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Latest Counts Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPThemeWassup_EM_LatestCounts_Hooks {

	function __construct() {

		add_filter(
			'latestcounts:allcontent:classes', 
			array($this, 'get_allcontent_classes')
		);
	}

	function get_allcontent_classes($classes) {

		$section_classes = array_filter(
			array(
				POPTHEME_WASSUP_EM_CAT_ALL,
				POPTHEME_WASSUP_EM_CAT_FUTURE,
				POPTHEME_WASSUP_EM_CAT_CURRENT,
				POPTHEME_WASSUP_EM_CAT_EVENTLINKS,
			)
		);
		foreach ($section_classes as $section_class) {
			$classes[] = EM_POST_TYPE_EVENT.'-'.$section_class;
		}

		return $classes;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPThemeWassup_EM_LatestCounts_Hooks();
