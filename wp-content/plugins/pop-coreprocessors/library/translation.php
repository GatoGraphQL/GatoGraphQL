<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Translation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

function gd_translate($content) {

	return apply_filters('gd_translate', $content);
}
function gd_translate_url($url) {

	return apply_filters('gd_translate_url', $url);
}