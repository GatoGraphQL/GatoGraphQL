<?php

/**---------------------------------------------------------------------------------------------------------------
 * CDNCore URLs
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_templatemanager:allowed_domains', 'pop_cdncore_allowedurls');
function pop_cdncore_allowedurls($allowed_domains) {

	// Add the Assets and Uploads CDNCore URLs as long as they were defined
	$allowed_domains = array_merge(
		$allowed_domains,
		array_filter(array(
			POP_CDN_ASSETS_URI,
			POP_CDN_UPLOADS_URI,
			POP_CDN_CONTENT_URI,
		))
	);

	return $allowed_domains;
}
