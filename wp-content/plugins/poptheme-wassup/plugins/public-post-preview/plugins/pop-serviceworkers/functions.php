<?php

// Inject data-sw-networkfirst="true" to the preview link in the Add Post messagefeedback
add_filter('gd_ppp_previewurl_link_params', 'pop_sw_reloadurl_linkattrs');

// Inject data-sw-networkfirst="true" to the preview link in the My Posts table
add_filter('GD_Template_Processor_Buttons:postpreview:params', 'pop_ppp_sw_previewbtn_networkfirst');
function pop_ppp_sw_previewbtn_networkfirst($params) {

	$params['data-sw-networkfirst'] = 'true';
	return $params;
}

