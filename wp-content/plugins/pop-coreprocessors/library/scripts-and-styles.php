<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Util functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

function get_googlemaps_url() {

	$googlemaps_url = 'https://maps.google.com/maps/api/js';
	if (POP_COREPROCESSORS_APIKEY_GOOGLEMAPS) {
		$googlemaps_url .= '?key='.POP_COREPROCESSORS_APIKEY_GOOGLEMAPS;
	}

	return $googlemaps_url;
}