<?php
class PPP_PoP_Initialization {

	function initialize(){

		load_plugin_textdomain('ppp-pop', false, dirname(plugin_basename(__FILE__)).'/languages');

		// Set the plugin namespace for the processors
		PoP_ServerUtils::set_namespace('aw');

		if (!is_admin()) {

			add_action("wp_enqueue_scripts", array($this, 'register_scripts'));
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Plugins
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'plugins/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'library/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * PoP Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'pop-library/load.php';
	}

	function register_scripts() {

		// Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
		if (!PoP_Frontend_ServerUtils::use_code_splitting()) {

			$js_folder = PPP_POP_URI.'/js';
			$dist_js_folder = $js_folder.'/dist';
			$libraries_js_folder = (PoP_Frontend_ServerUtils::use_minified_resources() ? $dist_js_folder : $js_folder).'/libraries';
			$suffix = PoP_Frontend_ServerUtils::use_minified_resources() ? '.min' : '';
			$bundles_js_folder = $dist_js_folder.'/bundles';

			if (PoP_Frontend_ServerUtils::use_bundled_resources()) {
				
				wp_register_script('public-post-preview-pop', $bundles_js_folder . '/public-post-preview-pop.bundle.min.js', array('pop', 'jquery'), PPP_POP_VERSION, true);
				wp_enqueue_script('public-post-preview-pop');
			}
			else {

				wp_register_script('ppp-pop-functions', $libraries_js_folder.'/ppp-functions'.$suffix.'.js', array('jquery', 'pop'), PPP_POP_VERSION, true);
				wp_enqueue_script('ppp-pop-functions');
			}
		}
	}
}