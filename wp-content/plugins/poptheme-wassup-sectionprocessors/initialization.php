<?php
class PoPTheme_Wassup_SectionProcessors_Initialization {

	function initialize(){

		load_plugin_textdomain('poptheme-wassup-sectionprocessors', false, dirname(plugin_basename(__FILE__)).'/languages');
		
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

		$css_folder = POPTHEME_WASSUP_SECTIONPROCESSORS_URI.'/css';
		$dist_css_folder = $css_folder . '/dist';
		if (PoP_Frontend_ServerUtils::use_minified_files()) {

			wp_register_style('poptheme-wassup-sectionprocessors', $dist_css_folder . '/poptheme-wassup-sectionprocessors.bundle.min.css', array('bootstrap'), POPTHEME_WASSUP_SECTIONPROCESSORS_VERSION);
			wp_enqueue_style('poptheme-wassup-sectionprocessors');
		}
		else {

			wp_register_style('poptheme-wassup-sectionprocessors', $css_folder.'/style.css', array('bootstrap'), POPTHEME_WASSUP_SECTIONPROCESSORS_VERSION);
			wp_enqueue_style('poptheme-wassup-sectionprocessors');
		}
	}
}