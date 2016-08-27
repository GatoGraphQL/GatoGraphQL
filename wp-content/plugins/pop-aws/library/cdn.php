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