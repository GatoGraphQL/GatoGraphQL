<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Post Gallery
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('wp_get_attachment_image_attributes', 'gd_wp_get_attachment_image_attributes_remove_quotes', 10, 2);
function gd_wp_get_attachment_image_attributes_remove_quotes($attr, $attachment) {

	// There's a bug in Wordpress: quotes (") don't get escaped properly, so Template Manager stops working...
	// So replace them with the html representation
	$attr['alt'] = str_replace('"', '&#8220;', $attr['alt']);
	return $attr;
}