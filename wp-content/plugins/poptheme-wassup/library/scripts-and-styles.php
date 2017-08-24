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

	echo '<link rel="stylesheet" href="' . POPTHEME_WASSUP_URI . '/css/admin.css" type="text/css" />';
}

function get_compatibility_js_files() {

	$files = array();
	if (PoP_Frontend_ServerUtils::use_minified_files()) {

		$files[] = 'https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js';
		$files[] = 'https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.1.0/respond.min.js';
	}
	else {

		$files[] = POPTHEME_WASSUP_URI.'/js/compat/html5shiv.min.js';
		$files[] = POPTHEME_WASSUP_URI.'/js/compat/respond.min.js';
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
add_filter('pop_header_inlinestyles:styles', 'wassup_inlinestyles');
function wassup_inlinestyles($styles) {

	// If the feed background image has been defined, then set the style
	if (POPTHEME_WASSUP_IMAGE_FEEDBACKGROUND) {
		
		$img = wp_get_attachment_image_src(POPTHEME_WASSUP_IMAGE_FEEDBACKGROUND, 'thumb-feed');
		$style = sprintf(
			'
				.thumb-feed {
					background-image: url(\'%s\');
					width: %spx;
					height: %spx;
					background-repeat: no-repeat;
					background-size: contain;
				}
			',
			$img[0],
			$img[1],
			$img[2]
		);
		// Keeping 468x267px for the thumb only works when the viewport is on the restricted sizes below
		// Otherwise, it may make the picture stick out of the column, making it very ugly
		// Specially with mobile phone, it makes the app have to scroll left to right
		$styles .= sprintf(
			'
			@media (min-width: 544px) and (max-width: 767px) {
				%s
			}
			',
			$style
		);
		$styles .= sprintf(
			'
			@media (min-width: 1200px) {
				%s
			}
			',
			$style
		);
	}

	return $styles;
}


/**---------------------------------------------------------------------------------------------------------------
 * Logged in classes: they depend on the domain, so they are added through PHP, not in the .css anymore
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('get_loggedin_domain_styles_placeholder', 'get_wassup_loggedin_domain_styles_placeholder');
function get_wassup_loggedin_domain_styles_placeholder($placeholder) {

	$placeholder .= 
		'
			.visible-loggedin-%1$s,
			body.loggedin-%1$s .visible-notloggedin-%1$s,
			.community-%1$s .visible-community-%1$s,
			.organization-%1$s .visible-organization-%1$s,
			.individual-%1$s .visible-individual-%1$s,
			.editor-%1$s .wp-media-buttons,'/*.pop-block.%1$s .wp-media-buttons,*/.'
			body.loggedin-%1$s.offline .editor-%1$s .wp-media-buttons,'/*body.loggedin-%1$s.offline .pop-block.%1$s .wp-media-buttons,*/.'
			.featuredimage-%1$s .loggedin-btn {'/*.pop-block.%1$s .loggedin-btn*/.'
				display: none !important;
			}

			.visible-notloggedin-%1$s,
			body.loggedin-%1$s .visible-loggedin-%1$s,
			body.loggedin-%1$s .visible-notloggedin-%1$s.visible-always,
			body.loggedin-%1$s.community-%1$s .visible-community-%1$s,
			body.loggedin-%1$s.community-%1$s .menu-%1$s .visible-community,
			body.loggedin-%1$s.organization-%1$s .visible-organization-%1$s,
			body.loggedin-%1$s.organization-%1$s .menu-%1$s .visible-organization,
			body.loggedin-%1$s.individual-%1$s .visible-individual-%1$s,
			body.loggedin-%1$s.individual-%1$s .menu-%1$s .visible-individual,
			body.loggedin-%1$s .editor-%1$s .wp-media-buttons {'/*body.loggedin-%1$s .pop-block.%1$s .wp-media-buttons {*/.'
				display: block !important;
			}
			body.loggedin-%1$s .featuredimage-%1$s .loggedin-btn {'/*body.loggedin-%1$s .pop-block.%1$s .loggedin-btn {*/.'
				display: inline-block !important;
			}

			';
	return $placeholder;
}