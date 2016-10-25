<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Thumbnails
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Thumb sizes
 * ---------------------------------------------------------------------------------------------------------------*/

add_action('after_setup_theme','gd_thumb_enable');
function gd_thumb_enable(){
	add_theme_support('post-thumbnails');
}


function gd_custom_thumb_sizes() {

	// add_image_size('thumb-lg', 620, 360, true);
	// Used for the Gallery, no cropping. Width must be 480px, height we don't care so much
	add_image_size('thumb-pagewide', 480, 3000);

	// Below two thumbs have the same proportions
	add_image_size('thumb-feed', 480, 267, true);
	add_image_size('thumb-md', 360, 200, true);
	
	add_image_size('thumb-sm', 75, 60, true);
	add_image_size('thumb-xs',  40, 40, true);
	add_image_size('favicon', 16, 16, true);
}
gd_custom_thumb_sizes();


/**---------------------------------------------------------------------------------------------------------------
 * Default thumbs
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_DataLoad_FieldProcessor_Posts:thumb:default', 'gd_thumb_default', 10, 2);
function gd_thumb_default($thumb_id, $post_id) {

	$cat = gd_get_the_main_category($post_id);
	if (($cat == POPTHEME_WASSUP_CAT_HIGHLIGHTS) && POPTHEME_WASSUP_IMAGE_NOFEATUREDIMAGEHIGHLIGHTPOST){

		return POPTHEME_WASSUP_IMAGE_NOFEATUREDIMAGEHIGHLIGHTPOST;
	}
	elseif (($cat == POPTHEME_WASSUP_CAT_WEBPOSTS) && POPTHEME_WASSUP_IMAGE_NOFEATUREDIMAGEWEBPOSTPOST){

		return POPTHEME_WASSUP_IMAGE_NOFEATUREDIMAGEWEBPOSTPOST;
	}

	return $thumb_id;
}

add_filter('GD_DataLoad_FieldProcessor_Posts:thumb:default', 'gd_thumb_defaultlink', 10, 2);
function gd_thumb_defaultlink($thumb_id, $post_id) {

	// For the links, re-use a different default thumb depending on the domain of the link
	$link_cats = apply_filters(
		'gd_thumb_defaultlink:link_categories', 
		array(
			POPTHEME_WASSUP_CAT_WEBPOSTLINKS
		)
	);
	$post_cats = gd_get_categories($post_id);
	if (array_intersect($link_cats, $post_cats)){

		// for the Link, its content IS the URL
		$post = get_post($post_id);
		$url = $post->post_content;

		// Get the domain of the URL, and return different results for different domains
		// Taken from https://stackoverflow.com/questions/276516/parsing-domain-from-url-in-php
		$parse = parse_url($url);
		$host = $parse['host']; // From 'http://google.com/pepe.html' it returns 'google.com'

		// List of $host => $thumb_id
		// Eg: 'guardian.com' => 53433
		$host_thumb_ids = apply_filters('gd_thumb_default:host_thumb_ids', array());

		if ($host_thumb_id = $host_thumb_ids[$host]) {

			return $host_thumb_id;
		}
	}

	return $thumb_id;
}