<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * URL Params
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_CLASS_LOADINGCONTENT', 'pop-loadingcontent');

add_filter('gd_jquery_constants', 'pop_frontend_jquery_classes');
function pop_frontend_jquery_classes($jquery_constants) {

	$jquery_constants['CLASS_LOADINGCONTENT'] = POP_CLASS_LOADINGCONTENT;
	return $jquery_constants;
}
