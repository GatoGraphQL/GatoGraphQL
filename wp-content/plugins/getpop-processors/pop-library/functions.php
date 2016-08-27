<?php

/**---------------------------------------------------------------------------------------------------------------
 * CDN URLs
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_templatemanager:allowed_urls', 'getpop_processors_allowedurls');
function getpop_processors_allowedurls($allowed_urls) {

	// Add the External Calendar Domain
	if (GETPOP_URL_EXTERNALWEBSITEDOMAIN) {

		$allowed_urls[] = GETPOP_URL_EXTERNALWEBSITEDOMAIN;		
	}

	return $allowed_urls;
}