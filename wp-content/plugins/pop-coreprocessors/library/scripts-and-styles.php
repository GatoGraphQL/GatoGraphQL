<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Util functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

function get_googlemaps_url($add_version = false) {

	$googlemaps_url = 'https://maps.google.com/maps/api/js';
	if (POP_COREPROCESSORS_APIKEY_GOOGLEMAPS) {
		$googlemaps_url .= '?key='.POP_COREPROCESSORS_APIKEY_GOOGLEMAPS;
	}

	if ($add_version) {
			
		$version = get_bloginfo( 'version' );
		$googlemaps_url = add_query_arg('ver', $version, $googlemaps_url);
	}

	return $googlemaps_url;
}


/**---------------------------------------------------------------------------------------------------------------
 * Logged in classes: they depend on the domain, so they are added through PHP, not in the .css anymore
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('pop_header_inlinestyles:styles', 'wassup_loggedin_styles');
function wassup_loggedin_styles($styles) {

	// if ($loggedinuserdata_domains = GD_Template_Processor_UserAccountUtils::get_loggedinuserdata_domains()) {
		
	// 	foreach ($loggedinuserdata_domains as $domain) {

	// 		$styles .= get_loggedin_domain_styles($domain);
	// 	}
	// }
	$styles .= get_loggedin_domain_styles(get_site_url());
	return $styles;
}
function get_loggedin_domain_styles($domain) {

	$domainId = GD_TemplateManager_Utils::get_domain_id($domain);
	return sprintf(
		get_loggedin_domain_styles_placeholder(),
		$domainId
	);
}
function get_loggedin_domain_styles_placeholder() {

	// Allow PoP Theme and WSL to add its own styles
	return apply_filters(
		'get_loggedin_domain_styles_placeholder',
		''
	);
}

