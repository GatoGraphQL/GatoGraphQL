<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Scripts and styles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * preloading fonts
 * ---------------------------------------------------------------------------------------------------------------*/
if (!is_admin()) {
	add_action( 'wp_head', 'pop_bootstrapprocessors_preloadfonts');
}
function pop_bootstrapprocessors_preloadfonts() {

	// It is 2 levels down from file "wp-content/plugins/poptheme-wassup/css/libraries/custom.bootstrap.css",
	// or its PROD version "wp-content/plugins/poptheme-wassup/css/dist/libraries/custom.bootstrap.min.css"
	$font_url = PoP_Frontend_ServerUtils::access_externalcdn_resources() ?
		'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/fonts/glyphicons-halflings-regular.woff2' : 
		POP_BOOTSTRAPPROCESSORS_URI.'/css/includes/fonts/glyphicons-halflings-regular.woff2';
	printf(
		'<link rel="preload" href="%s" as "font" crossorigin type="font/woff2">', 
		$font_url
	);
}