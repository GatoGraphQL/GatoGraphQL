<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD Locations Map
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_jquery_constants', 'gd_jquery_constants_locations_map_latlng');
function gd_jquery_constants_locations_map_latlng($jquery_constants) {

	$values = apply_filters('gd_locationsmap_latlng', array());
	
	if (!empty($values)) {
		$jquery_constants['LOCATIONSMAP_LAT'] = $values['lat'];
		$jquery_constants['LOCATIONSMAP_LNG'] = $values['lng'];
		$jquery_constants['LOCATIONSMAP_ZOOM'] = $values['zoom'];
		$jquery_constants['LOCATIONSMAP_1MARKER_ZOOM'] = $values['1marker_zoom'];
	}
	
	return $jquery_constants;
}