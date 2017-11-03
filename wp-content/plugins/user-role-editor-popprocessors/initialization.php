<?php
class URE_PoPProcessors_Initialization {

	function initialize(){

		load_plugin_textdomain('ure-popprocessors', false, dirname(plugin_basename(__FILE__)).'/languages');

		// Set the plugin namespace for the processors
		PoP_TemplateIDUtils::set_namespace('ah');

		require_once 'config/load.php';
		require_once 'pop-library/load.php';
		require_once 'library/load.php';
		require_once 'plugins/load.php';

		if (!is_admin()) {

			add_action("wp_enqueue_scripts", array($this, 'register_scripts'));
		}
	}

	function register_scripts() {

		// Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
		if (!PoP_Frontend_ServerUtils::use_code_splitting()) {

			$js_folder = URE_POPPROCESSORS_URI.'/js';
			$dist_js_folder = $js_folder.'/dist';
			$bundles_js_folder = $dist_js_folder.'/bundles';

			if (PoP_Frontend_ServerUtils::use_bundled_resources()) {
				
				wp_register_script('ure-popprocessors-templates', $bundles_js_folder . '/user-role-editor-popprocessors.templates.bundle.min.js', array(), URE_POPPROCESSORS_VERSION, true);
				wp_enqueue_script('ure-popprocessors-templates');
			}
			else {

				/** Templates Sources */
				$this->enqueue_templates_scripts();
			}
		}
	}

	function enqueue_templates_scripts() {

		$folder = URE_POPPROCESSORS_URI.'/js/dist/templates/';

		wp_enqueue_script('ure-layoutuser-memberstatus-tmpl', $folder.'ure-layoutuser-memberstatus.tmpl.js', array('handlebars'), URE_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('ure-layoutuser-memberprivileges-tmpl', $folder.'ure-layoutuser-memberprivileges.tmpl.js', array('handlebars'), URE_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('ure-layoutuser-membertags-tmpl', $folder.'ure-layoutuser-membertags.tmpl.js', array('handlebars'), URE_POPPROCESSORS_VERSION, true);
		// wp_enqueue_script('ure-layoutuser-communities-tmpl', $folder.'ure-layoutuser-communities.tmpl.js', array('handlebars'), URE_POPPROCESSORS_VERSION, true);
		wp_enqueue_script('ure-layoutuser-typeahead-selected-filterbycommunity-tmpl', $folder.'ure-layoutuser-typeahead-selected-filterbycommunity.tmpl.js', array('handlebars'), URE_POPPROCESSORS_VERSION, true);
	}
}