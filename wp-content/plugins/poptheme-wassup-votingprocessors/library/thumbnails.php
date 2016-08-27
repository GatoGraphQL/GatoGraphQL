<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Thumbnails
 *
 * ---------------------------------------------------------------------------------------------------------------*/


/**---------------------------------------------------------------------------------------------------------------
 * Default thumbs
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_DataLoad_FieldProcessor_Posts:thumb:default', 'votingprocessors_thumb_defaulthighlight', 10, 2);
function votingprocessors_thumb_defaulthighlight($thumb_id, $post_id) {

	$cat = gd_get_the_main_category($post_id);
	if (($cat == POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES) && POPTHEME_WASSUP_VOTINGPROCESSORS_IMAGE_NOFEATUREDIMAGEOPINIONATEDVOTEPOST){

		return POPTHEME_WASSUP_VOTINGPROCESSORS_IMAGE_NOFEATUREDIMAGEOPINIONATEDVOTEPOST;
	}

	return $thumb_id;
}