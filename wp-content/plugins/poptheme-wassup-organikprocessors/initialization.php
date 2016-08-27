<?php
class PoPTheme_Wassup_OrganikProcessors_Initialization {

	function initialize(){

		load_plugin_textdomain('poptheme-wassup-organikprocessors', false, dirname(plugin_basename(__FILE__)).'/languages');
		
		/**---------------------------------------------------------------------------------------------------------------
		 * Global Variables and Configuration
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'config/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the PoP Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'pop-library/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'library/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the plugins' libraries
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'plugins/load.php';
	
		if (!is_admin()) {

			// After PoPTheme MESYM
			add_action('wp_print_styles', array($this, 'register_styles'), 110);
		}
	}

	function register_styles() {

		$css_folder = POPTHEME_WASSUP_ORGANIKPROCESSORS_URI.'/css';
		$dist_css_folder = $css_folder . '/dist';
		if (PoP_Frontend_ServerUtils::use_minified_files()) {

			wp_register_style('poptheme-wassup-organikprocessors', $dist_css_folder . '/poptheme-wassup-organikprocessors.bundle.min.css', array('bootstrap'), POPTHEME_WASSUP_ORGANIKPROCESSORS_VERSION);
			wp_enqueue_style('poptheme-wassup-organikprocessors');
		}
		else {

			wp_register_style('poptheme-wassup-organikprocessors', $css_folder.'/style.css', array('bootstrap'), POPTHEME_WASSUP_ORGANIKPROCESSORS_VERSION);
			wp_enqueue_style('poptheme-wassup-organikprocessors');
		}
	}
}