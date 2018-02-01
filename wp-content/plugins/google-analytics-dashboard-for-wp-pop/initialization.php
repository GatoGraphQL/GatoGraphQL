<?php
class GADWP_PoP_Initialization {

	function initialize(){

		load_plugin_textdomain('gadwp-pop', false, dirname(plugin_basename(__FILE__)).'/languages');

		// Set the plugin namespace for the processors
		PoP_TemplateIDUtils::set_namespace('ax');

		if (!is_admin()) {

			add_action("wp_enqueue_scripts", array($this, 'register_scripts'));
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * PoP Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'pop-library/load.php';
	}

	function register_scripts() {

		// Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
		if (!PoP_Frontend_ServerUtils::use_code_splitting()) {

			$js_folder = GADWP_POP_URL.'/js';
			$dist_js_folder = $js_folder.'/dist';
			$libraries_js_folder = (PoP_Frontend_ServerUtils::use_minified_resources() ? $dist_js_folder : $js_folder).'/libraries';
			$suffix = PoP_Frontend_ServerUtils::use_minified_resources() ? '.min' : '';
			$bundles_js_folder = $dist_js_folder.'/bundles';

			if (PoP_Frontend_ServerUtils::use_bundled_resources()) {
				
				wp_register_script('google-analytics-dashboard-for-wp-pop', $bundles_js_folder . '/google-analytics-dashboard-for-wp-pop.bundle.min.js', array('pop', 'jquery'), GADWP_POP_VERSION, true);
				wp_enqueue_script('google-analytics-dashboard-for-wp-pop');
			}
			else {

				wp_register_script('gadwp-pop-functions', $libraries_js_folder.'/gadwp-functions'.$suffix.'.js', array('jquery', 'pop'), GADWP_POP_VERSION, true);
				wp_enqueue_script('gadwp-pop-functions');
			}
		}
	}
}