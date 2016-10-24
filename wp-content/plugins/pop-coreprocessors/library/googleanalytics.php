<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Google Analytics
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_jquery_constants', 'gd_jquery_constants_googleanalytics');
function gd_jquery_constants_googleanalytics($jquery_constants) {

	// $key = apply_filters('gd_googleanalytics_key', '');
	
	// if ($key) {
	// 	$jquery_constants['GOOGLEANALYTICS'] = $key;
	// }

	if (POP_COREPROCESSORS_APIKEY_GOOGLEANALYTICS) {
		$jquery_constants['GOOGLEANALYTICS'] = POP_COREPROCESSORS_APIKEY_GOOGLEANALYTICS;
	}
	
	return $jquery_constants;
}