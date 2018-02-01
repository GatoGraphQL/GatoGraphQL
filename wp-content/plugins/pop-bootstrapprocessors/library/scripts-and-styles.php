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

	printf(
		'<link rel="preload" href="%s" as "font" crossorigin type="font/woff2">', 
		get_bootstrap_font_url()
	);
}
function get_bootstrap_font_url($pathkey = null) {

	if (!$pathkey) {

		if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {
			
			$pathkey = 'external';
		}
		elseif (PoP_Frontend_ServerUtils::use_code_splitting() && PoP_Frontend_ServerUtils::loading_bundlefile()) {

			$pathkey = 'bundlefile';
		}
		else {

			$pathkey = 'local';
		}
	}

	return get_bootstrap_font_path($pathkey).'/fonts/glyphicons-halflings-regular.woff2';
}
function get_bootstrap_font_path($pathkey) {

	switch ($pathkey) {

		case 'external':

			return 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7';
		
		case 'bundlefile':

			$filegenerator = PoP_ResourceLoader_FileGenerator_BundleFiles_Utils::get_filegenerator(PoP_Frontend_ServerUtils::get_enqueuefile_type(true), POP_RESOURCELOADER_RESOURCETYPE_CSS, POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR);
			// dirname: Up 1 level from folder where the bundle(group) files were generated
			return dirname($filegenerator->get_url());

		case 'local':
		default:

			return POP_BOOTSTRAPPROCESSORS_URL.'/css/includes';
	}
}