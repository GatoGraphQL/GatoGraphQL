<?php

/**---------------------------------------------------------------------------------------------------------------
 * CDN URLs
 * ---------------------------------------------------------------------------------------------------------------*/

// Priority 10: execute just after the "website-environment" plugin has set all the environment constants
// That is needed to set POP_CDN_ASSETS_URI before
add_action('plugins_loaded', 'pop_cdn_assetsrc_init', 10);
function pop_cdn_assetsrc_init() {

	// Use the assets url instead of the site url for all the scripts and styles
	if (POP_CDN_ASSETS_URI && (POP_CDN_ASSETS_URI != get_site_url())) {

		add_filter('style_loader_src', 'pop_cdn_assetsrc');
		add_filter('script_loader_src', 'pop_cdn_assetsrc');
		add_filter('includes_url', 'pop_cdn_assetsrc');
		add_filter('plugins_url', 'pop_cdn_assetsrc');
		add_filter('stylesheet_directory_uri', 'pop_cdn_assetsrc');
	}
}
function pop_cdn_assetsrc($src) {
	
	// Replace the home with the CDN URL
	$home = get_site_url();
	if (substr($src, 0, strlen($home)) == get_site_url()) {
		return POP_CDN_ASSETS_URI.substr($src, strlen($home));
	}
	return $src;
}
