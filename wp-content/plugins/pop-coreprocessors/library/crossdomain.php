<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Content functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/
// Add support for Cross-Domain to Plupload
add_filter('plupload_default_settings', 'pop_core_plupload_default_settings');
function pop_core_plupload_default_settings($defaults) {
	
	// Allow to send cookies, so we can upload files with our loggedin user
	$defaults['required_features'] = "send_browser_cookies";

	return $defaults;
}