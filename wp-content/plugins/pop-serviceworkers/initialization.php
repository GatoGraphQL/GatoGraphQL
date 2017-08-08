<?php
class PoP_ServiceWorkers_Initialization {

	function initialize(){

		load_plugin_textdomain('pop-serviceworkers', false, dirname(plugin_basename(__FILE__)).'/languages');

		// Set the plugin namespace for the processors
		PoP_ServerUtils::set_namespace('a9');

		if (!is_admin()) {

			add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
			add_action('wp_head', array($this, 'header'));
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Constants/Configuration for functionalities needed by the plug-in
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'config/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Vendor library
		 * ---------------------------------------------------------------------------------------------------------------*/
		// require_once 'vendor/autoload.php';
		// require_once 'vendor/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Kernel
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'kernel/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Plug-ins Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'plugins/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the PoP Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'pop-library/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'library/load.php';
	}

	function header() {

		// Print the reference to the manifest file
		global $pop_serviceworkers_manager;
		printf(
			'<link rel="manifest" href="%s">',
			$pop_serviceworkers_manager->get_fileurl('manifest.json')
		);
	}

	function register_scripts() {

		$js_folder = POP_SERVICEWORKERS_URI.'/js';
		$libraries_js_folder = $js_folder.'/libraries';
		$dist_js_folder = $js_folder.'/dist';

		if (PoP_Frontend_ServerUtils::use_minified_files()) {
			
			wp_register_script('pop-serviceworkers', $dist_js_folder . '/pop-serviceworkers.bundle.min.js', array(), POP_SERVICEWORKERS_VERSION, true);
			wp_enqueue_script('pop-serviceworkers');
		}
		else {

			wp_register_script('pop-serviceworkers-functions', $libraries_js_folder.'/sw.js', array('pop'), POP_SERVICEWORKERS_VERSION, true);
			wp_enqueue_script('pop-serviceworkers-functions');
		}

		// This file is generated dynamically, so it can't be added to any bundle or minified
		global $pop_serviceworkers_manager;
		wp_register_script('pop-serviceworkers-registrar', $pop_serviceworkers_manager->get_fileurl('sw-registrar.js'), array(), POP_SERVICEWORKERS_VERSION, true);
		wp_enqueue_script('pop-serviceworkers-registrar');
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $PoP_ServiceWorkers_Initialization;
$PoP_ServiceWorkers_Initialization = new PoP_ServiceWorkers_Initialization();