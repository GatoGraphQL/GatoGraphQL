<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Scripts and styles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Change styles according to the domain
 * ---------------------------------------------------------------------------------------------------------------*/
// add_filter('pop_header_inlinestyles:styles', 'wassup_multidomain_inlinestyles');
// function wassup_multidomain_inlinestyles($styles) {

// 	// Add the background color for the home domain only. For other domains, it will be fetched on runtime, through CODE_INITIALIZEDOMAIN
// 	$styles .= get_multidomain_bgcolor_style(get_site_url());
// 	return $styles;
// }
// function get_multidomain_bgcolor_style($domain) {

// 	// Add the background color for the home domain only. For other domains, it will be fetched on runtime, through CODE_INITIALIZEDOMAIN
// 	$domain_bgcolors = PoPTheme_Wassup_MultiDomain_Utils::get_multidomain_bgcolors();
// 	if ($bgcolor = $domain_bgcolors[$domain]) {

// 		return sprintf(
// 			get_multidomain_bgcolor_style_placeholder(),
// 			GD_TemplateManager_Utils::get_domain_id($domain),
// 			$bgcolor
// 		);
// 	}

// 	return '';
// }
add_filter('GD_Template_Processor_DomainCodes:code:styles', 'get_multidomain_bgcolor_codestyle', 10, 2);
function get_multidomain_bgcolor_codestyle($styles, $domain) {

	global $popthemewassup_multidomainstyles_filerenderer;
	foreach ($popthemewassup_multidomainstyles_filerenderer->get() as $filereproduction) {

		$filereproduction->setDomain($domain);
	}
	$styles .= $popthemewassup_multidomainstyles_filerenderer->render();
	// $styles .= get_multidomain_bgcolor_style($domain); // Add the background color for the domain.
	return $styles;
}
// function get_multidomain_bgcolor_style_placeholder() {

// 	// Allow Events Manager to override the styles, for the Events Calendar
// 	return apply_filters(
// 		'multidomain_bgcolor_style:style_placeholder',
// 		'
// 			#ps-main .pop-multidomain .blockinner-row.%1$s {
// 				background-color: %2$s;
// 			}
// 		'
// 	);
// }