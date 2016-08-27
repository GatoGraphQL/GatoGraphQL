<?php
class WSL_PoPProcessors_Initialization {

	function initialize(){

		load_plugin_textdomain('wsl-popprocessors', false, dirname(plugin_basename(__FILE__)).'/languages');

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

		$folder = WSL_POPPROCESSORS_URI.'/js';

		if (PoP_Frontend_ServerUtils::use_minified_files()) {
			
			$folder .= '/dist';
			wp_register_script('wsl-popprocessors-templates', $folder . '/wordpress-social-login-popprocessors.templates.bundle.min.js', array(), WSL_POPPROCESSORS_VERSION, true);
			wp_enqueue_script('wsl-popprocessors-templates');

			wp_register_script('wsl-popprocessors', $folder . '/wordpress-social-login-popprocessors.bundle.min.js', array('pop', 'jquery'), WSL_POPPROCESSORS_VERSION, true);
			wp_enqueue_script('wsl-popprocessors');
		}
		else {

			$folder .= '/libraries';
			
			wp_register_script('wsl-popprocessors-functions', $folder.'/wsl-functions.js', array('jquery', 'pop'), WSL_POPPROCESSORS_VERSION, true);
			wp_enqueue_script('wsl-popprocessors-functions');

			/** Templates Sources */
			$this->enqueue_templates_scripts();
		}
	}

	function enqueue_templates_scripts() {

		$folder = WSL_POPPROCESSORS_URI.'/js/dist/templates/';

		wp_enqueue_script('wsl-networklinks-tmpl', $folder.'wsl-networklinks.tmpl.js', array('handlebars'), WSL_POPPROCESSORS_VERSION, true);
	}
}