<?php
class PoPTheme_Wassup_EM_Initialization {

	function __construct() {

		$this->initialize();
	}

	function initialize() {

		if (!is_admin()) {

			add_action('wp_print_styles', array($this, 'register_styles'), 50);
		}
	}

	function register_styles() {

		$css_folder = POPTHEME_WASSUP_URI.'/css';
		$dist_css_folder = $css_folder . '/dist';
		$templates_css_folder = (PoP_Frontend_ServerUtils::use_minified_resources() ? $dist_css_folder : $css_folder).'/templates';
		$suffix = PoP_Frontend_ServerUtils::use_minified_resources() ? '.min' : '';

		// if (!PoP_Frontend_ServerUtils::use_bundled_resources() && !PoP_Frontend_ServerUtils::use_code_splitting()) {
		if (!PoP_Frontend_ServerUtils::use_bundled_resources() && !PoP_Frontend_ServerUtils::include_resources_in_body()) {

			wp_register_style('poptheme-wassup-em-calendar', $templates_css_folder . '/plugins/events-manager/calendar'.$suffix.'.css', array(), POPTHEME_WASSUP_VERSION, 'screen');
			wp_enqueue_style('poptheme-wassup-em-calendar');

			wp_register_style('poptheme-wassup-em-map', $templates_css_folder . '/plugins/events-manager/map'.$suffix.'.css', array(), POPTHEME_WASSUP_VERSION, 'screen');
			wp_enqueue_style('poptheme-wassup-em-map');	
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_EM_Initialization();
