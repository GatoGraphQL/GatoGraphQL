<?php
class PoP_BaseProcessors_Initialization {

	function initialize(){

		load_plugin_textdomain('pop-baseprocessors', false, dirname(plugin_basename(__FILE__)).'/languages');

		// Set the plugin namespace for the processors
		PoP_TemplateIDUtils::set_namespace('a3');

		if (!is_admin()) {

			add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Kernel Override
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'kernel/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the PoP Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'pop-library/load.php';
	}

	function register_scripts() {

		// Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
		if (!PoP_Frontend_ServerUtils::use_code_splitting()) {

			$js_folder = POP_BASEPROCESSORS_URL.'/js';
			$dist_js_folder = $js_folder.'/dist';
			$bundles_js_folder = $dist_js_folder.'/bundles';
			
			if (PoP_Frontend_ServerUtils::use_bundled_resources()) {
				
				wp_register_script('pop-baseprocessors-templates', $bundles_js_folder . '/pop-baseprocessors.templates.bundle.min.js', array(), POP_BASEPROCESSORS_VERSION, true);
				wp_enqueue_script('pop-baseprocessors-templates');
			}
			else {

				/** Templates Sources */
				$this->enqueue_templates_scripts();
			}
		}
	}

	function enqueue_templates_scripts() {

		$folder = POP_BASEPROCESSORS_URL.'/js/dist/templates/';

		wp_enqueue_script('block-tmpl', $folder.'block.tmpl.js', array('handlebars'), POP_BASEPROCESSORS_VERSION, true);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $PoP_BaseProcessors_Initialization;
$PoP_BaseProcessors_Initialization = new PoP_BaseProcessors_Initialization();