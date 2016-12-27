<?php

/**---------------------------------------------------------------------------------------------------------------
 * CDN URLs
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_templatemanager:allowed_urls', 'pop_cdn_allowedurl');
function pop_cdn_allowedurl($allowed_urls) {

	// Add the Assets and Uploads CDN URLs as long as they were defined
	$allowed_urls = array_merge(
		$allowed_urls,
		array_filter(array(
			POP_AWS_CDN_ASSETS_URI,
			POP_AWS_CDN_UPLOADS_URI,
		))
	);

	return $allowed_urls;
}

// Priority 10: execute just after the "website-environment" plugin has set all the environment constants
// That is needed to set POP_AWS_CDN_ASSETS_URI before
add_action('plugins_loaded', 'pop_aws_cdn_assetsrc_init', 10);
function pop_aws_cdn_assetsrc_init() {

	// Use the assets url instead of the site url for all the scripts and styles
	if (POP_AWS_CDN_ASSETS_URI && (POP_AWS_CDN_ASSETS_URI != get_site_url())) {

		add_filter('style_loader_src', 'pop_aws_cdn_assetsrc');
		add_filter('script_loader_src', 'pop_aws_cdn_assetsrc');
		add_filter('includes_url', 'pop_aws_cdn_assetsrc');
		add_filter('plugins_url', 'pop_aws_cdn_assetsrc');
		add_filter('stylesheet_directory_uri', 'pop_aws_cdn_assetsrc');
	}
}
function pop_aws_cdn_assetsrc($src) {
	
	// Replace the home with the CDN URL
	$home = get_site_url();
	if (substr($src, 0, strlen($home)) == get_site_url()) {
		return POP_AWS_CDN_ASSETS_URI.substr($src, strlen($home));
	}
	return $src;
}
// if (POP_AWS_CDN_UPLOADS_URI && (POP_AWS_CDN_UPLOADS_URI != get_site_url())) {
// 	add_filter('content_url', 'pop_aws_cdn_uploadssrc');
// }
// function pop_aws_cdn_uploadssrc($src) {
	
// 	return str_replace(get_site_url(), POP_AWS_CDN_UPLOADS_URI, $src);
// }
