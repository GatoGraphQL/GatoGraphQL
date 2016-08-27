<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterAuthorPosts extends GD_Filter {

	function get_wildcard_filter() {
	
		// global $gd_filter_authorwildcardposts;
		// return $gd_filter_authorwildcardposts;
		return GD_FILTER_AUTHORWILDCARDPOSTS;
	}
}
