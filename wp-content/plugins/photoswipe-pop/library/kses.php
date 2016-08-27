<?php

// Allow 'data-size' in the html tags
add_filter('gd_allowedposttags', 'gd_photoswipe_allowedposttags');
function gd_photoswipe_allowedposttags($allowedposttags) {

	$allowedposttags['a']['data-size'] = true;
	return $allowedposttags;
}
