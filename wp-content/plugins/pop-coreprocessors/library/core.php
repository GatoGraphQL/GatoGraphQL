<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Post names / Menu post parents
 * ---------------------------------------------------------------------------------------------------------------*/

function gd_get_postname($post_id = null, $format = 'title') {

	if (is_null($post_id)) {
		global $post;
		$post_id = $post->ID;
	}	
	
	$postname = apply_filters('gd_postname', __('Post', 'pop-coreprocessors'), $post_id, $format);
	
	// Lowercase
	if ($format == 'lc' || $format == 'plural-lc') {
		$postname = strtolower($postname);
	}

	return apply_filters('gd_format_postname', $postname, $post_id, $format);
}

function gd_get_categoryname($cat_id, $format = 'title') {

	$catname = apply_filters('gd_catname', get_cat_name($cat_id), $cat_id, $format);
	
	// Lowercase
	if ($format == 'lc' || $format == 'plural-lc') {
		$catname = strtolower($catname);
	}

	return apply_filters('gd_format_catname', $catname, $cat_id, $format);
}

function gd_get_posticon($post_id = null) {

	if (is_null($post_id)) {
		global $post;
		$post_id = $post->ID;
	}	
	
	return apply_filters('gd_posticon', '', $post_id);
}

// Returns the id of the page showing all items of same $post_type, $cat as the one with $post_id
// (Used for painting navigation in single.php)
function gd_post_parentpageid($post_id = null) {

	if (is_null($post_id)) {
		global $post;
		$post_id = $post->post_ID;
	}

	return apply_filters('gd_post_parentpageid', 0, $post_id);
}

// Returns the id of the page showing all items of same role (Organizations / Individuals)
function gd_author_parentpageid($author_id = null) {

	return apply_filters('gd_author_parentpageid', 0, $author_id);
}



/* End of file core.php */
/* Location: ./core.php */