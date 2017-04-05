<?php

/**---------------------------------------------------------------------------------------------------------------
 * CDNCore URLs
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_templatemanager:allowed_urls', 'pop_cdncore_allowedurls');
function pop_cdncore_allowedurls($allowed_urls) {

	// Add the Assets and Uploads CDNCore URLs as long as they were defined
	$allowed_urls = array_merge(
		$allowed_urls,
		array_filter(array(
			POP_CDN_ASSETS_URI,
			POP_CDN_UPLOADS_URI,
			POP_CDN_CONTENT_URI,
		))
	);

	return $allowed_urls;
}
