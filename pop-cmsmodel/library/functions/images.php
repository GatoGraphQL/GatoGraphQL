<?php

function get_attachment_image_properties($imageid, $size = null) {
	
	$cmsapi = PoP_CMS_FunctionAPI_Factory::get_instance();
	$img = $cmsapi->wp_get_attachment_image_src($imageid, $size);
	return array(
		'src' => $img[0],
		'width' => $img[1],
		'height' => $img[2]
	);
}