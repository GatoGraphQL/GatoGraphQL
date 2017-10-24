<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Google Analytics
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_jquery_constants', 'gd_jquery_constants_googleanalytics');
function gd_jquery_constants_googleanalytics($jquery_constants) {

	if (POP_COREPROCESSORS_APIKEY_GOOGLEANALYTICS) {
		$jquery_constants['GOOGLEANALYTICS'] = POP_COREPROCESSORS_APIKEY_GOOGLEANALYTICS;
	}
	
	return $jquery_constants;
}