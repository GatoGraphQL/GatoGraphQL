<?php
class PoP_CDNCore_Initialization {

	function initialize(){

		load_plugin_textdomain('pop-cdn-core', false, dirname(plugin_basename(__FILE__)).'/languages');

		// Set the plugin namespace for the processors
		PoP_ServerUtils::set_namespace('a7');

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

		$js_folder = POP_CDNCORE_URI.'/js';
		$libraries_js_folder = $js_folder.'/libraries';
		$dist_js_folder = $js_folder.'/dist';

		if (PoP_Frontend_ServerUtils::use_minified_files()) {
			
			wp_register_script('pop-cdn-core', $dist_js_folder . '/pop-cdn-core.bundle.min.js', array(), POP_CDNCORE_VERSION, true);
			wp_enqueue_script('pop-cdn-core');
		}
		else {

			wp_register_script('pop-cdn-core-functions', $libraries_js_folder.'/cdn.js', array('pop'), POP_CDNCORE_VERSION, true);
			wp_enqueue_script('pop-cdn-core-functions');

			wp_register_script('pop-cdn-core-thumbprints', $libraries_js_folder.'/cdn-thumbprints.js', array('pop'), POP_CDNCORE_VERSION, true);
			wp_enqueue_script('pop-cdn-core-thumbprints');

			wp_register_script('pop-cdn-core-core-hooks', POP_CDNCORE_URI.'/plugins/pop-coreprocessors/js/libraries/cdn-hooks.js', array('pop-cdn-core-functions'), POP_CDNCORE_VERSION, true);
			wp_enqueue_script('pop-cdn-core-core-hooks');
		}

		// This file is generated dynamically, so it can't be added to any bundle or minified
		// That's why we use pop_version() as its version, so upgrading the website will fetch again this file
		global $pop_cdncore_manager;
		wp_register_script('pop-cdn-core-config', $pop_cdncore_manager->get_fileurl('cdn-config.js'), array(), pop_version(), true);
		wp_enqueue_script('pop-cdn-core-config');
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $PoP_CDNCore_Initialization;
$PoP_CDNCore_Initialization = new PoP_CDNCore_Initialization();