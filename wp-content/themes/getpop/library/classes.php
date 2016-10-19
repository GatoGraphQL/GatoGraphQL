<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * All CSS Classes
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter("gd_classes_body", 'getpop_classes');
function getpop_classes($body_classes) {

	// Allow GetPoP and GetPoP Demo websites to have different backgrounds
	$body_classes[] = GetPoP_Utils::is_demo() ? 'getpop-demo' : 'getpop';	
	return $body_classes;
}

