<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * URL Params
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_URLPARAM_CONTENTSOURCE', 'source');
define ('GD_URE_URLPARAM_CONTENTSOURCE_ORGANIZATION', 'org');
define ('GD_URE_URLPARAM_CONTENTSOURCE_COMMUNITY', 'com');

function gd_ure_get_default_contentsource() {

	return apply_filters('gd_ure_get_default_contentsource', GD_URE_URLPARAM_CONTENTSOURCE_COMMUNITY);
}
