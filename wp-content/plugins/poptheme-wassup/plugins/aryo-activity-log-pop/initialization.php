<?php
class PopThemeWassup_AAL_Initialization {

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

		if (!PoP_Frontend_ServerUtils::use_bundled_resources() && !PoP_Frontend_ServerUtils::use_code_splitting()) {

			wp_register_style('poptheme-wassup-aal-notification-layout', $templates_css_folder . '/plugins/aryo-activity-log-pop/notification-layout'.$suffix.'.css', array(), POPTHEME_WASSUP_VERSION, 'screen');
			wp_enqueue_style('poptheme-wassup-aal-notification-layout');
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PopThemeWassup_AAL_Initialization();
