<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * URL Params
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URLPARAM_URECONTENTSOURCE', 'source');
define ('GD_URLPARAM_URECONTENTSOURCE_ORGANIZATION', 'org');
define ('GD_URLPARAM_URECONTENTSOURCE_COMMUNITY', 'com');

function gd_ure_get_default_contentsource() {

	return apply_filters('gd_ure_get_default_contentsource', GD_URLPARAM_URECONTENTSOURCE_COMMUNITY);
}
