<?php
class PoP_CDNCore_Initialization {

	function initialize(){

		load_plugin_textdomain('pop-cdn-core', false, dirname(plugin_basename(__FILE__)).'/languages');

		// Set the plugin namespace for the processors
		PoP_TemplateIDUtils::set_namespace('a7');

		if (!is_admin()) {

			add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Kernel
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'kernel/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the PoP Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'pop-library/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'library/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Plugins Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'plugins/load.php';
	}

	function register_scripts() {

		// Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
		if (!PoP_Frontend_ServerUtils::use_code_splitting()) {

			$js_folder = POP_CDNCORE_URI.'/js';
			$dist_js_folder = $js_folder.'/dist';
			$libraries_js_folder = (PoP_Frontend_ServerUtils::use_minified_resources() ? $dist_js_folder : $js_folder).'/libraries';
			$suffix = PoP_Frontend_ServerUtils::use_minified_resources() ? '.min' : '';
			$bundles_js_folder = $dist_js_folder.'/bundles';
		
			if (PoP_Frontend_ServerUtils::use_bundled_resources()) {
				
				wp_register_script('pop-cdn-core', $bundles_js_folder . '/pop-cdn-core.bundle.min.js', array(), POP_CDNCORE_VERSION, true);
				wp_enqueue_script('pop-cdn-core');
			}
			else {

				wp_register_script('pop-cdn-core-functions', $libraries_js_folder.'/cdn'.$suffix.'.js', array('pop'), POP_CDNCORE_VERSION, true);
				wp_enqueue_script('pop-cdn-core-functions');

				wp_register_script('pop-cdn-core-thumbprints', $libraries_js_folder.'/cdn-thumbprints'.$suffix.'.js', array('pop'), POP_CDNCORE_VERSION, true);
				wp_enqueue_script('pop-cdn-core-thumbprints');

				wp_register_script('pop-cdn-core-core-hooks', $libraries_js_folder.'/plugins/pop-coreprocessors/cdn-hooks'.$suffix.'.js', array('pop-cdn-core-functions'), POP_CDNCORE_VERSION, true);
				wp_enqueue_script('pop-cdn-core-core-hooks');
			}

			// This file is generated dynamically, so it can't be added to any bundle or minified
			// That's why we use pop_version() as its version, so upgrading the website will fetch again this file
			global $pop_cdncore_configfile_generator;
			wp_register_script('pop-cdn-core-config', $pop_cdncore_configfile_generator->get_fileurl(), array(), pop_version(), true);
			wp_enqueue_script('pop-cdn-core-config');
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $PoP_CDNCore_Initialization;
$PoP_CDNCore_Initialization = new PoP_CDNCore_Initialization();