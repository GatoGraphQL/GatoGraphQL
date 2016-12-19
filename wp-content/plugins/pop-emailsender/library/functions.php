<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Email functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('wp_mail', 'pop_emailsender_decode_subject');
function pop_emailsender_decode_subject($atts) {

	//decode entities, but run kses first just in case users use placeholders containing html
	$atts['subject'] = html_entity_decode(wp_kses_data($atts['subject']));
	return $atts;
}