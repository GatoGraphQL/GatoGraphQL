<?php
class WSL_PoPProcessors_Initialization {

	function initialize(){

		load_plugin_textdomain('wsl-popprocessors', false, dirname(plugin_basename(__FILE__)).'/languages');

		// Set the plugin namespace for the processors
		PoP_ServerUtils::set_namespace('ai');

		if (!is_admin()) {

			add_action("wp_enqueue_scripts", array($this, 'register_scripts'));
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Plugins
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'plugins/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * PoP Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'pop-library/load.php';
	}

	function register_scripts() {

		// Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
		if (!PoP_Frontend_ServerUtils::use_code_splitting()) {

			$js_folder = WSL_POPPROCESSORS_URI.'/js';
			$dist_js_folder = $js_folder.'/dist';
			$libraries_js_folder = (PoP_Frontend_ServerUtils::use_minified_resources() ? $dist_js_folder : $js_folder).'/libraries';
			$suffix = PoP_Frontend_ServerUtils::use_minified_resources() ? '.min' : '';
			$bundles_js_folder = $dist_js_folder.'/bundles';

			if (PoP_Frontend_ServerUtils::use_bundled_resources()) {
				
				wp_register_script('wsl-popprocessors-templates', $bundles_js_folder . '/wordpress-social-login-popprocessors.templates.bundle.min.js', array(), WSL_POPPROCESSORS_VERSION, true);
				wp_enqueue_script('wsl-popprocessors-templates');

				wp_register_script('wsl-popprocessors', $bundles_js_folder . '/wordpress-social-login-popprocessors.bundle.min.js', array('pop', 'jquery'), WSL_POPPROCESSORS_VERSION, true);
				wp_enqueue_script('wsl-popprocessors');
			}
			else {

				wp_register_script('wsl-popprocessors-functions', $libraries_js_folder.'/wsl-functions'.$suffix.'.js', array('jquery', 'pop'), WSL_POPPROCESSORS_VERSION, true);
				wp_enqueue_script('wsl-popprocessors-functions');

				/** Templates Sources */
				$this->enqueue_templates_scripts();
			}
		}
	}

	function enqueue_templates_scripts() {

		$folder = WSL_POPPROCESSORS_URI.'/js/dist/templates/';

		wp_enqueue_script('wsl-networklinks-tmpl', $folder.'wsl-networklinks.tmpl.js', array('handlebars'), WSL_POPPROCESSORS_VERSION, true);
	}
}