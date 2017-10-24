<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * URL Params
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_PARAMS_PREVIEW', 'preview');
define ('POP_PARAMS_PATH', 'path');

add_filter('gd_jquery_constants', 'ppp_pop_jquery_constants');
function ppp_pop_jquery_constants($jquery_constants) {

	$jquery_constants['PARAMS_PREVIEW'] = POP_PARAMS_PREVIEW;
	$jquery_constants['PARAMS_PATH'] = POP_PARAMS_PATH;
	return $jquery_constants;
}
