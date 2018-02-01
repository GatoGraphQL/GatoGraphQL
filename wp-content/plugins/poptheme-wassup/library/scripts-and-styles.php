<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Scripts and styles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**
 * Register my own admin.css for adding new styles. Adds the function 'add_my_stylesheet' to the wp_enqueue_scripts action.
 */
// Load GD admin css
add_action( 'admin_print_styles', 'add_gd_admin_stylesheet' );	
function add_gd_admin_stylesheet() {

	echo '<link rel="stylesheet" href="' . POPTHEME_WASSUP_URL . '/css/admin.css" type="text/css" />';
}

function get_compatibility_js_files() {

	$files = array();
	if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {

		$files[] = 'https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js';
		$files[] = 'https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.1.0/respond.min.js';
	}
	else {

		$files[] = POPTHEME_WASSUP_URL.'/js/compat/html5shiv.min.js';
		$files[] = POPTHEME_WASSUP_URL.'/js/compat/respond.min.js';
	}

	return $files;
}


/**---------------------------------------------------------------------------------------------------------------
 * Inline styles
 * ---------------------------------------------------------------------------------------------------------------*/
if (!is_admin()) {
	add_action( 'wp_head', 'pop_header_inlinestyles');
}
function pop_header_inlinestyles() {

	if ($inlinestyles = apply_filters('pop_header_inlinestyles:styles', '')) {

		printf('<style type="text/css">%s</style>', $inlinestyles);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Inline styles implementation
 * ---------------------------------------------------------------------------------------------------------------*/
// add_filter('pop_header_inlinestyles:styles', 'wassup_inlinestyles');
// function wassup_inlinestyles($styles) {

// 	// If the feed background image has been defined, then set the style
// 	if (POPTHEME_WASSUP_IMAGE_FEEDBACKGROUND) {
		
// 		$img = wp_get_attachment_image_src(POPTHEME_WASSUP_IMAGE_FEEDBACKGROUND, 'thumb-feed');
// 		$style = sprintf(
// 			'
// 				.thumb-feed {
// 					background-image: url(\'%s\');
// 					width: %spx;
// 					height: %spx;
// 					background-repeat: no-repeat;
// 					background-size: contain;
// 				}
// 			',
// 			$img[0],
// 			$img[1],
// 			$img[2]
// 		);
// 		// Keeping 468x267px for the thumb only works when the viewport is on the restricted sizes below
// 		// Otherwise, it may make the picture stick out of the column, making it very ugly
// 		// Specially with mobile phone, it makes the app have to scroll left to right
// 		$styles .= sprintf(
// 			'
// 			@media (min-width: 544px) and (max-width: 767px) {
// 				%s
// 			}
// 			',
// 			$style
// 		);
// 		$styles .= sprintf(
// 			'
// 			@media (min-width: 1200px) {
// 				%s
// 			}
// 			',
// 			$style
// 		);
// 	}

// 	return $styles;
// }


/**---------------------------------------------------------------------------------------------------------------
 * Logged in classes: they depend on the domain, so they are added through PHP, not in the .css anymore
 * ---------------------------------------------------------------------------------------------------------------*/
// add_filter('get_loggedin_domain_styles_placeholder', 'get_wassup_loggedin_domain_styles_placeholder');
// function get_wassup_loggedin_domain_styles_placeholder($placeholder) {

// 	$placeholder .= 
// 		'
// 			.visible-loggedin-%1$s,
// 			body.loggedin-%1$s .visible-notloggedin-%1$s,
// 			.community-%1$s .visible-community-%1$s,
// 			.organization-%1$s .visible-organization-%1$s,
// 			.individual-%1$s .visible-individual-%1$s,
// 			.editor-%1$s .wp-media-buttons,
// 			body.loggedin-%1$s.offline .editor-%1$s .wp-media-buttons,
// 			.featuredimage-%1$s .loggedin-btn {
// 				display: none !important;
// 			}

// 			.visible-notloggedin-%1$s,
// 			body.loggedin-%1$s .visible-loggedin-%1$s,
// 			body.loggedin-%1$s .visible-notloggedin-%1$s.visible-always,
// 			body.loggedin-%1$s.community-%1$s .visible-community-%1$s,
// 			body.loggedin-%1$s.community-%1$s .menu-%1$s .visible-community,
// 			body.loggedin-%1$s.organization-%1$s .visible-organization-%1$s,
// 			body.loggedin-%1$s.organization-%1$s .menu-%1$s .visible-organization,
// 			body.loggedin-%1$s.individual-%1$s .visible-individual-%1$s,
// 			body.loggedin-%1$s.individual-%1$s .menu-%1$s .visible-individual,
// 			body.loggedin-%1$s .editor-%1$s .wp-media-buttons {
// 				display: block !important;
// 			}
// 			body.loggedin-%1$s .featuredimage-%1$s .loggedin-btn {
// 				display: inline-block !important;
// 			}
// 			';
// 	return $placeholder;
// }

/**---------------------------------------------------------------------------------------------------------------
 * preloading fonts
 * ---------------------------------------------------------------------------------------------------------------*/
if (!is_admin()) {
	add_action( 'wp_head', 'pop_header_preloadfonts');
}
function pop_header_preloadfonts() {

	// Always preload the "woff2" font only, no need for the others: all browsers that support preload, also support woff2
	// Read on https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/webfont-optimization#preload_your_webfont_resources
	$preload = '<link rel="preload" href="%s" as "font" crossorigin type="font/woff2">';
	printf(
		$preload, 
		get_wassup_font_url()
	);

	printf(
		$preload, 
		get_fontawesome_font_url()
	);
}
function get_wassup_font_url($pathkey = null) {

	if (!$pathkey) {

		if (PoP_Frontend_ServerUtils::use_code_splitting() && PoP_Frontend_ServerUtils::loading_bundlefile()) {

			$pathkey = 'bundlefile';
		}
		else {

			$pathkey = 'local';
		}
	}

	return get_wassup_font_path($pathkey).'/fonts/Rockwell.woff2';
}
function get_wassup_font_path($pathkey) {

	switch ($pathkey) {

		case 'bundlefile':

			$filegenerator = PoP_ResourceLoader_FileGenerator_BundleFiles_Utils::get_filegenerator(PoP_Frontend_ServerUtils::get_enqueuefile_type(true), POP_RESOURCELOADER_RESOURCETYPE_CSS, POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR);
			// dirname: Up 1 level from folder where the bundle(group) files were generated
			return dirname($filegenerator->get_url());

		case 'local':
		default:

			return POPTHEME_WASSUP_URL.'/css'.(PoP_Frontend_ServerUtils::use_minified_resources() ? '/dist' : '');
	}
}
function get_fontawesome_font_url($pathkey = null) {

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

	return get_fontawesome_font_path($pathkey).'/fonts/fontawesome-webfont.woff2?v=4.7.0';
}
function get_fontawesome_font_path($pathkey) {

	switch ($pathkey) {

		case 'external':

			return 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0';
		
		case 'bundlefile':

			$filegenerator = PoP_ResourceLoader_FileGenerator_BundleFiles_Utils::get_filegenerator(PoP_Frontend_ServerUtils::get_enqueuefile_type(true), POP_RESOURCELOADER_RESOURCETYPE_CSS, POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR);
			// dirname: Up 1 level from folder where the bundle(group) files were generated
			return dirname($filegenerator->get_url());

		case 'local':
		default:

			return POPTHEME_WASSUP_URL.'/css/includes';
	}	
}
