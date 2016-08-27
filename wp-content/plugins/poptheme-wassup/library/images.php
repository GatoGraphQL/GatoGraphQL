<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Styles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

function gd_logo($size = 'large') {

	$gd_logo = apply_filters('gd_get_logo', array());	
	return $gd_logo[$size];
}

function gd_images_background() {

	return apply_filters('gd_images_background', '');
}

function gd_images_welcome() {

	return apply_filters('gd_images_welcome', '');
}