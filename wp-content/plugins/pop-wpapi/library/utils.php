<?php

/**---------------------------------------------------------------------------------------------------------------
 * utils.php
 * ---------------------------------------------------------------------------------------------------------------*/
function get_post_excerpt($post_id) {

	$post = get_post($post_id);

	$readmore = sprintf(
		__('... <a href="%s">Read more</a>', 'pop-wpapi'),
		get_permalink($post_id)
	);
	$value = empty($post->post_excerpt) ? limit_string(strip_tags(strip_shortcodes($post->post_content)), 300, $readmore) : $post->post_excerpt;
	// $value = make_clickable($value);

	$value = apply_filters('the_excerpt', $value);
	return apply_filters('pop_excerpt', $value, $post_id);
}

function gd_get_thumb_id($post_id) {
			
	if ($thumb_id = get_post_thumbnail_id($post_id)) {

		return $thumb_id;
	}

	// Default
	return apply_filters('GD_DataLoad_FieldProcessor_Posts:thumb:default', POP_WPAPI_IMAGE_NOFEATUREDIMAGEPOST, $post_id);
}


function gd_get_avatar($user_id, $size) {
	
	$avatar = get_avatar($user_id, $size);
	// It doesn't work with images from Facebook, so replace with regex
	// Solution: http://stackoverflow.com/questions/2120779/regex-php-isolate-src-attribute-from-img-tag
	// $url = getHtmlAttribute($avatar, 'img', 'src');
	preg_match( '/src="([^"]*)"/i', $avatar, $array ) ;
	$url = $array[1];

	return array('src' => $url, 'size' => $size);
}

function gd_get_the_main_category($post_id = null, $return_id = true) {
	
	$cat = null;
	if (get_post_type($post_id) == 'post') {
		
		if ($cats = get_the_category($post_id)) {

			// Make sure that we got the Main category that we expect.
			// Eg: when creating a Story link, the post will be assigned 2 categories:
			// Story and (Story) Link. Even though category Link is child of Story, it is
			// still retrieved by Wordpress in position 0 (it's ordered alphabetically?)
			// So get list of forbidden cats, and iterate until the cat is not in that list
			$skip_main_cats = apply_filters('gd_main_category:skip', array());
			for ($i=0; $i < count($cats); $i++) { 
				$cat = $cats[$i];
				if (!in_array($cat->term_id, $skip_main_cats)) {
					break;
				}
			}
			
			if ($return_id) {
				$cat = $cat->term_id;
			}
		}
	}

	return apply_filters('gd_get_the_main_category', $cat, $post_id, $return_id);
}