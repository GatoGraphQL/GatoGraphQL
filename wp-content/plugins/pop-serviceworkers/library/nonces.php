<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**
 * Changes from PoP:
 * If enabling the Service Workers and caching the html, then we can't enable the nonce control anymore,
 * since that value will always be stale and give back the value false. At least for the current nonce of just 1 day
 * So make the nonce a tiny bit longer
 */
add_filter('nonce_life', 'pop_sw_nonce_life');
function pop_sw_nonce_life($nonce_life) {

	// 180 Days: this implies that the Service Workers cache must be regenerated every, at most, 180 days,
	// or otherwise the website will fail. Eg: upload images to the media gallery will fail
	return 180*DAY_IN_SECONDS;
}
