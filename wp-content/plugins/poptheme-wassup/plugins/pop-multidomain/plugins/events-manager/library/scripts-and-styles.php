<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Scripts and styles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Change styles according to the domain
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('multidomain_bgcolor_style:style_placeholder', 'get_em_multidomain_bgcolor_style_placeholder');
function get_em_multidomain_bgcolor_style_placeholder($placeholder) {

	$placeholder .= 
		'
			.pop-multidomain .fc-event.%1$s {
				background-color: %2$s;
			}
		';
	return $placeholder;
}