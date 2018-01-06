<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * CDN Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
add_action('init', 'init_wassup_cdn_image_attributes');
function init_wassup_cdn_image_attributes() {

	// If we have defined the Assets CDN, then we must set the images to use the crossorigin attribute,
	// so they can be cached in SW through CORS configuration
	if (defined('POP_CDN_ASSETS_URI') && POP_CDN_ASSETS_URI) {
		add_filter('gd_images_attributes', 'wassup_cdn_image_attributes');
	}
}
function wassup_cdn_image_attributes($attributes) {

	return $attributes.' crossorigin="anonymous"';
}