<?php
class PoP_BaseProcessors_Initialization {

	function initialize(){

		load_plugin_textdomain('pop-baseprocessors', false, dirname(plugin_basename(__FILE__)).'/languages');

		if (!is_admin()) {

			add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
		}

		/**---------------------------------------------------------------------------------------------------------------
		 * Kernel Override
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'kernel/load.php';
	}

	function register_scripts() {

		$js_folder = POP_BASEPROCESSORS_URI.'/js';
		$dist_js_folder = $js_folder.'/dist';

		if (PoP_Frontend_ServerUtils::use_minified_files()) {
			
			wp_register_script('pop-baseprocessors-templates', $dist_js_folder . '/pop-baseprocessors.templates.bundle.min.js', array(), POP_BASEPROCESSORS_VERSION, true);
			wp_enqueue_script('pop-baseprocessors-templates');
		}
		else {

			/** Templates Sources */
			$this->enqueue_templates_scripts();
		}
	}

	function enqueue_templates_scripts() {

		$folder = POP_BASEPROCESSORS_URI.'/js/dist/templates/';

		wp_enqueue_script('block-tmpl', $folder.'block.tmpl.js', array('handlebars'), POP_BASEPROCESSORS_VERSION, true);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $PoP_BaseProcessors_Initialization;
$PoP_BaseProcessors_Initialization = new PoP_BaseProcessors_Initialization();