<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * GD Locations Map
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_jquery_constants', 'gd_em_jquery_constants_locations');
function gd_em_jquery_constants_locations($jquery_constants) {

	$jquery_constants['LOCATIONSID_FIELDNAME'] = GD_TEMPLATE_FORMCOMPONENT_LOCATIONID;
	return $jquery_constants;
}