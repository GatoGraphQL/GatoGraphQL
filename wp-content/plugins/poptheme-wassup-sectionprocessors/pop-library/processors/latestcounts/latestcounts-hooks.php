<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Latest Counts Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPThemeWassup_SectionProcessors_LatestCounts_Hooks {

	function __construct() {

		add_filter(
			'latestcounts:allcontent:classes', 
			array($this, 'get_allcontent_classes')
		);
	}

	function get_allcontent_classes($classes) {

		$section_classes = array_filter(
			array(
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_LOCATIONPOSTLINKS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORIES,
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORYLINKS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTLINKS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONLINKS,
				POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_FEATURED,
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
new PoPThemeWassup_SectionProcessors_LatestCounts_Hooks();
