<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * VotingProcessors functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_get_the_main_category', 'gd_votingprocessors_get_category', 10, 3);
function gd_votingprocessors_get_category($cat, $post_id, $return_id) {

	if (get_post_type($post_id) == 'post') {

		// If it contains category OPINIONATEDVOTES then the main category is OPINIONATEDVOTES
		// This is because these posts have always 2 categories: OPINIONATEDVOTES AND OPINIONATEDVOTES_PRO/AGAINST/NEUTRAL,
		// but all the configuration is tied against OPINIONATEDVOTES
		$cats = get_the_category($post_id);
		foreach ($cats as $maybe_cat) {
			if ($maybe_cat->term_id == POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES) {
				if ($return_id) {
					return POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES;
				}
				return $maybe_cat;
			}
		}
	}

	return $cat;
}