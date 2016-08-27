<?php

/**---------------------------------------------------------------------------------------------------------------
 * Pages with multiple opens
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_TemplateManager_Utils:multiple_open', 'votingprocessors_multipleopenurls');
function votingprocessors_multipleopenurls($multiple_open) {

	if (is_page()) {

		global $post;
		$multiple = array(
			POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_ADDOPINIONATEDVOTE,
		);
		if (in_array($post->ID, $multiple)) {

			return true;
		}
	}

	return $multiple_open;
}

/**---------------------------------------------------------------------------------------------------------------
 * Latest Counts Categories
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_template:latestcounts:categories', 'votingprocessors_latestcountscategories');
function votingprocessors_latestcountscategories($categories) {

	// Show notifications also for latest votes
	return array_merge(
		$categories,
		array_filter(
			array( 
				POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES,
			)
		)
	);
}